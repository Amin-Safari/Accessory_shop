<?php

use App\Http\Controllers\PaymentCallbackController;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentTransaction;
use App\Models\Product;
use App\Models\User;
use App\Services\InventoryReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Payment\Facade\Payment;
use function Pest\Laravel\get;
use function Pest\Laravel\withoutExceptionHandling;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Mock the InventoryReservationService
    $this->inventoryService = mock(InventoryReservationService::class);
    $this->app->instance(InventoryReservationService::class, $this->inventoryService);

    // Create test data
    $this->user = User::factory()->create();
    $this->product = Product::factory()->create(['price' => 100000]);

    // Create order
    $this->order = Order::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'pending',
        'final_price' => 100000,
        'uuid' => 'test-uuid-123',
    ]);

    // Create order item
    OrderItem::factory()->create([
        'order_id' => $this->order->id,
        'product_id' => $this->product->id,
        'quantity' => 1,
        'unit_price' => 100000,
        'total_price' => 100000,
    ]);

    // Create payment transaction
    $this->transaction = PaymentTransaction::factory()->create([
        'order_id' => $this->order->id,
        'user_id' => $this->user->id,
        'amount' => 100000,
        'status' => 'pending',
        'transaction_id' => 'test-track-id-123',
    ]);
});

// Test 1: Successful payment verification
it('verifies payment successfully and redirects to step 4', function () {
    // Mock successful payment verification
    Payment::shouldReceive('amount->transactionId->verify')
        ->once()
        ->andReturn((object)['getReferenceId' => fn() => 'REF-123456']);

    // Mock inventory confirmation
    $this->inventoryService->shouldReceive('confirmReservation')
        ->once()
        ->withArgs(fn($order) => $order->id === $this->order->id);

    // Make the callback request
    $response = get(route('payment.callback', [
        'trackId' => 'test-track-id-123',
        'success' => '1',
    ]));

    // Assert redirect to checkout step 4
    $response->assertRedirect(route('checkout', ['step' => 4]));

    // Assert session has the order UUID
    $response->assertSessionHas('last_order_uuid', 'test-uuid-123');

    // Assert order status updated
    $this->order->refresh();
    expect($this->order->status)->toBe('paid');
    expect($this->order->tracking_code)->not->toBeNull();
    expect($this->order->paid_at)->not->toBeNull();

    // Assert transaction updated
    $this->transaction->refresh();
    expect($this->transaction->status)->toBe('completed');
    expect($this->transaction->reference_id)->toBe('REF-123456');
    expect($this->transaction->completed_at)->not->toBeNull();

    // Assert cart and checkout data cleared
    expect(Session::has('cart'))->toBeFalse();
    expect(Session::has('checkout.customer_info'))->toBeFalse();
});

// Test 2: Payment verification fails (InvalidPaymentException)
it('handles invalid payment verification gracefully', function () {
    // Mock failed verification
    Payment::shouldReceive('amount->transactionId->verify')
        ->once()
        ->andThrow(new InvalidPaymentException('Payment verification failed'));

    // Mock inventory release
    $this->inventoryService->shouldReceive('releaseInventory')
        ->once()
        ->withArgs(fn($order) => $order->id === $this->order->id);

    // Make the callback request
    $response = get(route('payment.callback', [
        'trackId' => 'test-track-id-123',
        'success' => '0',
    ]));

    // Assert redirect
    $response->assertRedirect(route('checkout', ['step' => 4]));
    $response->assertSessionHas('payment_error');

    // Assert order status updated
    $this->order->refresh();
    expect($this->order->status)->toBe('payment_failed');

    // Assert transaction updated
    $this->transaction->refresh();
    expect($this->transaction->status)->toBe('failed');
    expect($this->transaction->error_message)->toContain('تایید پرداخت ناموفق بود');
});

// Test 3: Missing trackId parameter
it('redirects with error when trackId is missing', function () {
    $response = get(route('payment.callback'));

    $response->assertRedirect(route('checkout', ['step' => 4]));
    $response->assertSessionHas('payment_error', 'اطلاعات پرداخت نامعتبر است.');

    // Order should remain unchanged
    $this->order->refresh();
    expect($this->order->status)->toBe('pending');
});

// Test 4: Transaction not found or already processed
it('redirects with error when transaction is not found', function () {
    $response = get(route('payment.callback', [
        'trackId' => 'non-existent-track-id',
    ]));

    $response->assertRedirect(route('checkout', ['step' => 4]));
    $response->assertSessionHas('payment_error', 'تراکنش یافت نشد یا قبلاً پردازش شده است.');
});

// Test 5: Transaction already completed (duplicate callback)
it('prevents processing already completed transaction', function () {
    // Mark transaction as completed
    $this->transaction->update(['status' => 'completed']);

    $response = get(route('payment.callback', [
        'trackId' => 'test-track-id-123',
    ]));

    $response->assertRedirect(route('checkout', ['step' => 4]));
    $response->assertSessionHas('payment_error', 'تراکنش یافت نشد یا قبلاً پردازش شده است.');
});

// Test 6: Order not found for transaction
it('handles missing order gracefully', function () {
    // Delete the order
    $this->order->delete();

    $response = get(route('payment.callback', [
        'trackId' => 'test-track-id-123',
    ]));

    $response->assertRedirect(route('checkout', ['step' => 4]));
    $response->assertSessionHas('payment_error', 'سفارش یافت نشد.');
});

// Test 7: Generic exception during verification
it('handles generic exceptions during verification', function () {
    // Mock generic exception
    Payment::shouldReceive('amount->transactionId->verify')
        ->once()
        ->andThrow(new \Exception('Database connection error'));

    // Mock inventory release
    $this->inventoryService->shouldReceive('releaseInventory')
        ->once();

    $response = get(route('payment.callback', [
        'trackId' => 'test-track-id-123',
    ]));

    $response->assertRedirect(route('checkout', ['step' => 4]));
    $response->assertSessionHas('payment_error', 'خطای سیستمی رخ داد. لطفاً با پشتیبانی تماس بگیرید.');

    // Assert transaction updated with error
    $this->transaction->refresh();
    expect($this->transaction->status)->toBe('failed');
    expect($this->transaction->error_message)->toContain('Database connection error');
});

// Test 8: Inventory release fails but payment is still marked as failed
it('handles inventory release failure gracefully', function () {
    // Mock failed verification
    Payment::shouldReceive('amount->transactionId->verify')
        ->once()
        ->andThrow(new InvalidPaymentException('Payment verification failed'));

    // Mock inventory release to throw exception
    $this->inventoryService->shouldReceive('releaseInventory')
        ->once()
        ->andThrow(new \Exception('Inventory release failed'));

    $response = get(route('payment.callback', [
        'trackId' => 'test-track-id-123',
    ]));

    // Should still redirect with error
    $response->assertRedirect(route('checkout', ['step' => 4]));

    // Transaction should still be marked as failed
    $this->transaction->refresh();
    expect($this->transaction->status)->toBe('failed');
});

// Test 9: Successful payment generates tracking code
it('generates unique tracking code on successful payment', function () {
    Payment::shouldReceive('amount->transactionId->verify')
        ->once()
        ->andReturn((object)['getReferenceId' => fn() => 'REF-123456']);

    $this->inventoryService->shouldReceive('confirmReservation')
        ->once();

    get(route('payment.callback', [
        'trackId' => 'test-track-id-123',
    ]));

    $this->order->refresh();
    expect($this->order->tracking_code)->toMatch('/^[A-Z0-9]+$/');
    expect(strlen($this->order->tracking_code))->toBeGreaterThan(5);
});

// Test 10: Log entries are created for debugging
it('logs successful and failed payments appropriately', function () {
    Log::shouldReceive('info')
        ->once()
        ->withArgs(function ($message, $context) {
            return $message === 'Payment completed successfully' &&
                $context['order_id'] === $this->order->id;
        });

    Log::shouldReceive('warning')->never();

    Payment::shouldReceive('amount->transactionId->verify')
        ->once()
        ->andReturn((object)['getReferenceId' => fn() => 'REF-123456']);

    $this->inventoryService->shouldReceive('confirmReservation')->once();

    get(route('payment.callback', [
        'trackId' => 'test-track-id-123',
    ]));
});

// Test 11: Failed payment logs warning
it('logs warning on failed payment', function () {
    Log::shouldReceive('warning')
        ->once()
        ->withArgs(function ($message, $context) {
            return $message === 'Payment verification failed' &&
                $context['trackId'] === 'test-track-id-123';
        });

    Log::shouldReceive('info')->never();

    Payment::shouldReceive('amount->transactionId->verify')
        ->once()
        ->andThrow(new InvalidPaymentException('Payment verification failed'));

    $this->inventoryService->shouldReceive('releaseInventory')->once();

    get(route('payment.callback', [
        'trackId' => 'test-track-id-123',
    ]));
});

// Test 12: Session data is properly cleaned
it('clears cart and checkout session data after successful payment', function () {
    // Set some session data
    Session::put('cart', ['item1', 'item2']);
    Session::put('checkout.customer_info', ['name' => 'Test User']);
    Session::put('some_other_data', 'should remain');

    Payment::shouldReceive('amount->transactionId->verify')
        ->once()
        ->andReturn((object)['getReferenceId' => fn() => 'REF-123456']);

    $this->inventoryService->shouldReceive('confirmReservation')->once();

    $response = get(route('payment.callback', [
        'trackId' => 'test-track-id-123',
    ]));

    // Cart and checkout data should be cleared
    expect(Session::has('cart'))->toBeFalse();
    expect(Session::has('checkout.customer_info'))->toBeFalse();

    // Other session data should remain
    expect(Session::has('some_other_data'))->toBeTrue();
});
