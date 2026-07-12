<?php

use Livewire\Component;

new class extends Component
{

    #[Computed]
    public function items()
    {
        // برگرداندن آیتم‌های سبد خرید از سشن
        $cart = session()->get('cart', []);

        // اگر سبد خرید خالی است، یک آرایه خالی برگردانید
        if (empty($cart)) {
            return [];
        }

        // اینجا می‌توانید محصولات را از دیتابیس دریافت کنید
        // برای مثال:
        // return Product::whereIn('id', array_keys($cart))->get();

        // برای دمو، داده‌های تستی برمی‌گردانیم
        return [
            [
                'id' => 1,
                'name' => 'هدفون بی‌سیم',
                'price' => 2500000,
                'quantity' => $cart[1] ?? 1,
                'image' => '🎧'
            ],
            [
                'id' => 2,
                'name' => 'کیف لپ‌تاپ',
                'price' => 1200000,
                'quantity' => $cart[2] ?? 1,
                'image' => '💼'
            ]
        ];
    }
};
