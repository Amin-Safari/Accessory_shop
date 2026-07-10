<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class ProductShow extends Component
{
    public $product;
    public int $quantity = 1;

    public function mount($slug)
    {
        $this->product = Product::query()
            ->where('slug', $slug)
            ->with('category')
            ->firstOrFail();
//        dd($this->product);
    }


    public function increment()
    {
        if ($this->quantity < $this->product->total) {
            $this->quantity++;
        }
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        $this->dispatch('add-to-cart', [
            'product_id' => $this->product->id,
            'quantity' => $this->quantity,
            'name' => $this->product->name,
            'price' => $this->product->final_price,
            'image' => $this->product->image_url,
        ]);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'محصول با موفقیت به سبد خرید اضافه شد'
        ]);
    }

    public function addToWishlist()
    {
        $this->dispatch('add-to-wishlist', [
            'product_id' => $this->product->id,
            'name' => $this->product->name,
        ]);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'محصول به علاقه‌مندی‌ها اضافه شد'
        ]);
    }

    public function render()
    {
        return view('livewire.product-show', [
            'relatedProducts' => $this->getRelatedProducts(),
        ]);
    }

    private function getRelatedProducts()
    {
        return Product::query()
            ->where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->active()
            ->take(8)
            ->get();
    }
}
