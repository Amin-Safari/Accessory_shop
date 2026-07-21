<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentTransaction;
use App\Models\ShippingMethod;
use App\Services\InventoryReservationService;
use Livewire\Component;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;

new class extends Component {
    public $shippingMethods = [];
    public $selectedShipping = null;
    public array $cartItems = [];
    public array $customerInfo = [];
    public float $subtotal = 0;
    public float $discountAmount = 0;
    public float $shippingCost = 0;
    public float $totalAmount = 0;
    public bool $isProcessing = false;
    public ?string $errorMessage = null;

    protected InventoryReservationService $inventoryService;

    public function boot(InventoryReservationService $inventoryService): void
    {
        $this->inventoryService = $inventoryService;
    }

    public function mount(): void
    {
        $this->loadCartItems();
        $this->loadCustomerInfo();
        $this->loadShippingMethods();
        $this->calculateTotals();

        // If no cart items or customer info, redirect back
        if (empty($this->cartItems) || empty($this->customerInfo)) {
            $this->dispatch('previousStep');
        }
    }

    public function loadCartItems(): void
    {
        $this->cartItems = session('cart', []);
    }

    public function loadCustomerInfo(): void
    {
        $this->customerInfo = session('checkout.customer_info', []);
    }

    public function loadShippingMethods(): void
    {
        $this->shippingMethods = ShippingMethod::get()
            ->toArray();

        if (!$this->selectedShipping && !empty($this->shippingMethods)) {
            $this->selectedShipping = $this->shippingMethods[0]['id'];
        }
    }

    public function selectShipping($id): void
    {
        $this->selectedShipping = $id;
        $this->calculateTotals();
    }

    public function updatedSelectedShipping(): void
    {
        $this->calculateTotals();
    }

    public function calculateTotals(): void
    {
        $this->subtotal = collect($this->cartItems)->sum(function ($item) {
            return $item['price'] * $item['qty'];
        });

        $this->discountAmount = collect($this->cartItems)->sum(function ($item) {
            return ($item['price'] * $item['qty'] * ($item['discount'] ?? 0)) / 100;
        });

        $selectedMethod = collect($this->shippingMethods)
            ->firstWhere('id', $this->selectedShipping);

        $this->shippingCost = $selectedMethod['price'] ?? 0;
        $this->totalAmount = $this->subtotal - $this->discountAmount + $this->shippingCost;
    }

    public function proceedToPayment(): void
    {
        $this->isProcessing = true;
        $this->errorMessage = null;

        // Validate shipping method is selected
        if (!$this->selectedShipping) {
            $this->errorMessage = 'لطفاً روش ارسال را انتخاب کنید.';
            $this->isProcessing = false;
            return;
        }

        // Validate inventory before proceeding
        $inventoryValidation = $this->inventoryService->validateCartInventory(
            collect($this->cartItems)->map(function ($item) {
                return [
                    'product_id' => $item['id'],
                    'product_name' => $item['title'],
                    'quantity' => $item['qty'],
                ];
            })->toArray()
        );

        if (!$inventoryValidation['valid']) {
            $this->errorMessage = implode('<br>', $inventoryValidation['errors']);
            $this->isProcessing = false;
            return;
        }

        try {
            DB::beginTransaction();

            // Create order
            $order = $this->createOrder();

            // Create order items
            $this->createOrderItems($order);

            // Reserve inventory
            $reserved = $this->inventoryService->reserveInventory($order);

            if (!$reserved) {
                throw new \Exception('موجودی کافی نیست. لطفاً چند لحظه دیگر تلاش کنید.');
            }

            // Create payment transaction
            $transaction = $this->createPaymentTransaction($order);

            DB::commit();

            // Redirect to payment gateway
            $this->redirectToZibalGateway($order, $transaction);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorMessage = $e->getMessage();
            \Log::error('Payment initiation failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        $this->isProcessing = false;
    }

    protected function createOrder(): Order
    {
        return Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'ORD-' . time() . '-' . rand(1000, 9999),
            'name' => $this->customerInfo['name'],
            'phone' => $this->customerInfo['phone'],
            'address' => $this->customerInfo['address'],
            'province' => $this->customerInfo['province_id'] ?? '',
            'city' => $this->customerInfo['city_id'] ?? '',
            'postal_code' => $this->customerInfo['postal_code'] ?? '',
            'price' => $this->subtotal,
            'discount_amount' => $this->discountAmount,
            'shipping_cost' => $this->shippingCost,
            'final_price' => $this->totalAmount,
            'status' => 'pending',
            'shipping_method' => $this->getShippingMethodName(),
            'notes' => $this->customerInfo['notes'] ?? null,
        ]);
    }

    protected function getShippingMethodName(): string
    {
        $method = collect($this->shippingMethods)
            ->firstWhere('id', $this->selectedShipping);

        return $method['name'] ?? 'نامشخص';
    }

    protected function createOrderItems(Order $order): void
    {
        foreach ($this->cartItems as $item) {
            $discountAmount = ($item['price'] * $item['qty'] * ($item['discount'] ?? 0)) / 100;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['qty'],
                'unit_price' => $item['price'],
                'discount' => $item['discount'] ?? 0,
                'discount_amount' => $discountAmount,
                'total_price' => ($item['price'] * $item['qty']) - $discountAmount,
            ]);
        }
    }

    protected function createPaymentTransaction(Order $order): PaymentTransaction
    {
        return PaymentTransaction::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'gateway' => 'zibal', // Using Zibal gateway
            'amount' => $this->totalAmount,
            'currency' => 'IRR',
            'status' => 'initiated',
            'gateway_request' => json_encode([
                'amount' => $this->totalAmount,
                'order_id' => $order->id,
                'shipping_method' => $this->getShippingMethodName()
            ]),
            'expires_at' => now()->addMinutes(30),
        ]);
    }

    protected function redirectToZibalGateway(Order $order, PaymentTransaction $transaction): void
    {
        try {
            // Zibal specific configuration
            $config = [
                'zibal' => [
                    'merchant' => config('services.zibal.merchant_id'),
                    'callback_url' => route('payment.callback'),
                ]
            ];

            // Create invoice with order details
            $invoice = (new Invoice())
                ->amount($this->totalAmount)
                ->detail('order_id', $order->id)
                ->detail('transaction_id', $transaction->id)
                ->detail('shipping_method', $this->getShippingMethodName());

            // Initialize payment with Zibal driver
            $payment = Payment::via('zibal')->config($config);

            // Purchase and get transaction ID
            $payment->purchase($invoice, function ($driver, $transactionId) use ($transaction) {
                // Update transaction with gateway's transaction ID
                $transaction->update([
                    'transaction_id' => $transactionId,
                    'status' => 'pending',
                ]);
            });

            // Get payment URL and redirect
            $response = $payment->pay();

            // For Zibal, we need to handle the response properly
            if (method_exists($response, 'render')) {
                $html = $response->render();

                // Extract redirect URL from Zibal's response
                if (preg_match('/action=["\']([^"\']+)["\']/', $html, $matches)) {
                    $url = $matches[1];
                    $this->redirect($url);
                } else {
                    // If it's a direct redirect, check for redirect URL
                    $redirectUrl = $response->getRedirectUrl();
                    if ($redirectUrl) {
                        $this->redirect($redirectUrl);
                    } else {
                        throw new \Exception('لینک پرداخت دریافت نشد');
                    }
                }
            } else {
                // Handle direct response object
                $redirectUrl = $response->getRedirectUrl() ?? $response->getTargetUrl();
                if ($redirectUrl) {
                    $this->redirect($redirectUrl);
                } else {
                    throw new \Exception('لینک پرداخت دریافت نشد');
                }
            }

        } catch (\Exception $e) {
            \Log::error('Zibal gateway redirect failed: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'transaction_id' => $transaction->id,
            ]);

            // Mark transaction as failed if redirect fails
            $transaction->update([
                'status' => 'failed',
                'error_message' => 'خطا در اتصال به درگاه پرداخت: ' . $e->getMessage(),
            ]);

            throw new \Exception('خطا در اتصال به درگاه پرداخت. لطفاً مجدداً تلاش کنید.');
        }
    }
};
