<?php


use Livewire\Component;
use App\Models\Product;

new class extends Component {
    public Product $product;
    public int $quantity = 1;

    public function mount(Product $product, int $quantity): void
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function increment(): void
    {
        if ($this->quantity < $this->product->total) {
            $this->quantity++;
            $this->dispatch('quantity-updated', quantity: $this->quantity);
        }
    }

    public function decrement(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
            $this->dispatch('quantity-updated', quantity: $this->quantity);
        }
    }

    public function addToCart(int $productId): void
    {
        $product = Product::query()
            ->findOrFail($productId);

        $cart = session('cart', []);
        if ($product->total && $product->is_active) {

            $cart[$product->id] = [

                'id' => $product->id,

                'title' => $product->name,

                'price' => $product->price,

                'discount' => $product->discount,

                'image' => $product->image_url,

                'qty' => $this->quantity,

            ];

            session()->put('cart', $cart);

            $this->dispatch('cart-updated');

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'محصول به سبد خرید اضافه شد.'
            ]);
        }
    }


    public function addToWishlist(): void
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

    public function shareProduct(): void
    {
        $this->dispatch('share', [
            'url' => route('products.show', $this->product->slug),
            'title' => $this->product->name,
        ]);
    }
};
