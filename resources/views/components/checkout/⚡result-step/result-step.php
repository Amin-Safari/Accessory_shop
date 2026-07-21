<?php

use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Services\InventoryReservationService;
use Livewire\Component;

new class extends Component
{
    public ?Order $order = null;
    public string $status = 'loading'; // loading, success, failed
    public ?string $errorMessage = null;
    public ?string $trackingCode = null;
    public ?string $referenceCode = null;

    public function mount(): void
    {
        $this->loadOrderResult();
    }

    protected function loadOrderResult(): void
    {
        // Get order UUID from flashed session data (set by callback controller)
        $orderUuid = session('last_order_uuid');
        if ($orderUuid) {
            // Load the order from database
            $this->order = Order::with(['items.product', 'paymentTransactions'])
                ->where('id', $orderUuid)
                ->first();
            if ($this->order) {
                // Determine status based on order status in database
                if ($this->order->status === 'paid') {
                    $this->status = 'success';
                } elseif (in_array($this->order->status, ['payment_failed', 'failed'])) {
                    $this->status = 'failed';
                    $this->errorMessage = $this->order->paymentTransaction->error_message
                        ?? 'پرداخت ناموفق بود.';
                } else {
                    $this->status = 'loading';
                }
            } else {
                $this->status = 'failed';
                $this->errorMessage = 'سفارش یافت نشد.';
            }
        } else {
            // Check if there's an error message from callback
            if (session()->has('payment_error')) {
                $this->status = 'failed';
                $this->errorMessage = session('payment_error');
            } else {
                // No payment result available
                $this->status = 'failed';
                $this->errorMessage = 'اطلاعات پرداخت در دسترس نیست.';
            }
        }
    }

    public function retryPayment(): void
    {
        if (!$this->order) {
            // Redirect back to payment step
            $this->redirect(route('checkout', ['step' => 3]));
        }
    }

    public function goToOrders(): void
    {
        $this->redirect(route('user.orders'));
    }
    public function goToShop(): void
    {
        $this->redirect(route('shop'));
    }
};
