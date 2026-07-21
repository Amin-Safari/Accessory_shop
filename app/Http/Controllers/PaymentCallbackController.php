<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Services\InventoryReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Payment\Facade\Payment;

class PaymentCallbackController extends Controller
{
    protected InventoryReservationService $inventoryService;

    public function __construct(InventoryReservationService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function __invoke(Request $request)
    {
        // Validate callback has required parameters
        if (!$request->has('trackId')) {
            Log::error('Payment callback missing trackId');
            return redirect()->route('checkout', ['step' => 4])
                ->with('payment_error', 'اطلاعات پرداخت نامعتبر است.');
        }

        $trackId = $request->input('trackId');

        try {
            // Find the pending transaction by trackId
            $transaction = PaymentTransaction::where('transaction_id', $trackId)
                ->where('status', 'pending')
                ->first();

            if (!$transaction) {
                Log::error('Transaction not found or already processed', ['trackId' => $trackId]);
                return redirect()->route('checkout', ['step' => 4])
                    ->with('payment_error', 'تراکنش یافت نشد یا قبلاً پردازش شده است.');
            }

            $order = Order::with('items.product')->find($transaction->order_id);

            if (!$order) {
                Log::error('Order not found for transaction', ['transaction_id' => $transaction->id]);
                return redirect()->route('checkout', ['step' => 4])
                    ->with('payment_error', 'سفارش یافت نشد.');
            }

            // Verify the payment
            $receipt = Payment::amount($transaction->amount)
                ->transactionId($trackId)
                ->verify();

            // Payment verified successfully
            $this->handleSuccessfulPayment($order, $transaction, $receipt->getReferenceId(), $receipt->getDetails());

            // Store order UUID in session for Step4 to read
            session()->flash('last_order_uuid', $order->id);

            // Clear cart and checkout data
            session()->forget(['cart', 'checkout.customer_info']);

            return redirect()->route('checkout', ['step' => 4])->with('last_order_uuid', $order->id);

        } catch (InvalidPaymentException $e) {
            // Payment verification failed (fraud, amount mismatch, etc.)
            Log::warning('Payment verification failed', [
                'trackId' => $trackId,
                'error' => $e->getMessage(),
            ]);

            if (isset($transaction) && $transaction) {
                $this->handleFailedPayment(
                    $order ?? null,
                    $transaction,
                    'تایید پرداخت ناموفق بود: ' . $e->getMessage()
                );
            }

            return redirect()->route('checkout', ['step' => 4])
                ->with('payment_error', 'پرداخت ناموفق بود. لطفاً مجدداً تلاش کنید.');

        } catch (\Exception $e) {
            Log::error('Payment callback error', [
                'trackId' => $trackId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'date' => now()
            ]);

            if (isset($transaction) && $transaction) {
                $this->handleFailedPayment(
                    $order ?? null,
                    $transaction,
                    'خطا در پردازش پرداخت: ' . $e->getMessage()
                );
            }

            return redirect()->route('checkout', ['step' => 4])
                ->with('payment_error', 'خطای سیستمی رخ داد. لطفاً با پشتیبانی تماس بگیرید.');
        }
    }

    protected function handleSuccessfulPayment(Order $order, PaymentTransaction $transaction, string $referenceId, array $details): void
    {
        if ($referenceId == ""){
            $referenceId = null;
        }
        // Confirm inventory reservation
        $this->inventoryService->confirmReservation($order);

        // Generate tracking code
        $trackingCode = Order::generateTrackingCode();
        // Update order
        $order->update([
            'tracking_code' => $trackingCode,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Update transaction
        $transaction->update([
            'status' => 'completed',
            'reference_id' => $referenceId ?? null,
            'gateway_response' => json_encode($details),
            'completed_at' => now(),
        ]);

        Log::info('Payment completed successfully', [
            'order_id' => $order->id,
            'order_uuid' => $order->uuid,
            'tracking_code' => $trackingCode,
            'reference_id' => $referenceId ?? null,
            'gateway_response' => json_encode($details),
        ]);
    }

    protected function handleFailedPayment(?Order $order, PaymentTransaction $transaction, string $errorMessage): void
    {
        // Release inventory if order exists
        if ($order) {
            $this->inventoryService->releaseInventory($order);

            $order->update([
                'status' => 'payment_failed',
                'admin_notes' => $errorMessage,
            ]);
        }

        // Update transaction
        $transaction->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);

        Log::warning('Payment failed', [
            'transaction_id' => $transaction->id,
            'order_id' => $order?->id,
            'error' => $errorMessage,
        ]);
    }
}
