<?php

use App\Models\Product;
use Livewire\Component;

new class extends Component
{
    public Product $product;
    public function addToCart(int $productId): void
    {
        $product = Product::query()
            ->findOrFail($productId);

        $cart = session('cart', []);
        if ($product->total && $product->is_active) {
            if (isset($cart[$product->id])) {

                $cart[$product->id]['qty']++;

            } else {

                $cart[$product->id] = [

                    'id' => $product->id,

                    'title' => $product->name,

                    'price' => $product->price,

                    'discount' => $product->discount,

                    'image' => $product->image_url,

                    'qty' => 1,

                ];

            }
            session()->put('cart', $cart);

            $this->dispatch('cart-updated');

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'محصول به سبد خرید اضافه شد.'
            ]);
        }
    }

};
