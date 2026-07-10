<?php


use Livewire\Component;
use App\Models\Product;

new class extends Component
{
    public Product $product;
    public int $quantity = 1;

    public function mount(Product $product, int $quantity)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function increment()
    {
        if ($this->quantity < $this->product->total) {
            $this->quantity++;
            $this->dispatch('quantity-updated', quantity: $this->quantity);
        }
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
            $this->dispatch('quantity-updated', quantity: $this->quantity);
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

    public function shareProduct()
    {
        $this->dispatch('share', [
            'url' => route('products.show', $this->product->slug),
            'title' => $this->product->name,
        ]);
    }
};
