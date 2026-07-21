<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class InventoryReservationService
{
    /**
     * رزرو موقت موجودی برای آیتم‌های سفارش
     */
    public function reserveInventory(Order $order): bool
    {
        return DB::transaction(function () use ($order) {
            foreach ($order->items as $orderItem) {
                $product = Product::lockForUpdate()->find($orderItem->product_id);

                if (!$product) {
                    throw new \Exception("Product {$orderItem->product_id} not found");
                }

                // بررسی موجودی فعال
                if (!$product->is_active || $product->total < $orderItem->quantity) {
                    Log::warning("Insufficient stock for product {$product->id}. Required: {$orderItem->quantity}, Available: {$product->stock}");
                    return false;
                }

                // کاهش موقت موجودی
                $product->decrement('total', $orderItem->quantity);

                // بروزرسانی اطلاعات رزرو
                $orderItem->update([
                    'reserved_quantity' => $orderItem->quantity,
                    'reserved_at' => now(),
                    'reserved_until' => now()->addMinutes(30),
                ]);

                Log::info("Reserved {$orderItem->quantity} units of product {$product->id} for order {$order->id}");
            }

            // بروزرسانی وضعیت سفارش
            $order->update(['status' => 'awaiting_payment']);

            return true;
        }, 3); // 3 تلاش در صورت deadlock
    }

    /**
     * آزادسازی موجودی رزرو شده (در صورت شکست پرداخت)
     */
    public function releaseInventory(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $orderItem) {
                if ($orderItem->reserved_quantity > 0) {
                    $product = Product::lockForUpdate()->find($orderItem->product_id);

                    if ($product) {
                        $product->increment('total', $orderItem->reserved_quantity);
                        Log::info("Released {$orderItem->reserved_quantity} units of product {$product->id} from order {$order->id}");
                    }

                    $orderItem->update([
                        'reserved_quantity' => 0,
                        'reserved_until' => null,
                    ]);
                }
            }
        }, 3);
    }

    /**
     * تایید نهایی رزرو (پس از پرداخت موفق)
     */
    public function confirmReservation(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $orderItem) {
                $orderItem->update([
                    'reserved_quantity' => 0,
                    'reserved_until' => null,
                ]);
            }

            $order->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        }, 3);
    }

    /**
     * بررسی و آزادسازی رزروهای منقضی شده (برای کرون جاب)
     */
    public function releaseExpiredReservations(): int
    {
        $expiredItems = OrderItem::where('reserved_quantity', '>', 0)
            ->where('reserved_until', '<', now())
            ->whereHas('order', function ($query) {
                $query->whereIn('status', ['awaiting_payment', 'pending']);
            })
            ->get();

        $count = 0;

        foreach ($expiredItems as $item) {
            DB::transaction(function () use ($item, &$count) {
                $product = Product::lockForUpdate()->find($item->product_id);

                if ($product) {
                    $product->increment('total', $item->reserved_quantity);
                }

                $item->update([
                    'reserved_quantity' => 0,
                    'reserved_until' => null,
                ]);

                // علامت‌گذاری تراکنش پرداخت به عنوان timed_out
                $latestTransaction = $item->order->latestPaymentTransaction;
                if ($latestTransaction && $latestTransaction->status === 'pending') {
                    $latestTransaction->markAsTimedOut();
                }

                // بروزرسانی وضعیت سفارش
                $item->order->update(['status' => 'payment_failed']);

                $count++;
            }, 3);
        }

        Log::info("Released {$count} expired reservations");
        return $count;
    }

    /**
     * اعتبارسنجی موجودی سبد خرید قبل از شروع فرآیند پرداخت
     */
    public function validateCartInventory(array $cartItems): array
    {
        $validation = [
            'valid' => true,
            'errors' => [],
            'items' => [],
        ];

        foreach ($cartItems as $item) {
            $product = Product::find($item['product_id']);

            if (!$product || !$product->is_active) {
                $validation['valid'] = false;
                $validation['errors'][] = "محصول {$item['product_name']} در دسترس نیست";
                continue;
            }

            if ($product->total < $item['quantity']) {
                $validation['valid'] = false;
                $validation['errors'][] = "موجودی {$item['product_name']} کافی نیست (موجودی: {$product->stock})";
            }

            $validation['items'][] = [
                'product' => $product,
                'requested_quantity' => $item['quantity'],
                'available_stock' => $product->total,
            ];
        }

        return $validation;
    }
}
