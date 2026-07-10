<?php


use Livewire\Component;
use App\Models\Product;

new class extends Component
{
    public Product $product;
    public string $activeImage = '';

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->activeImage = $product->image_url;
    }

    public function setActiveImage(string $url)
    {
        $this->activeImage = $url;
    }

    public function render()
    {
        return view('components.products.⚡gallery.gallery', [
            'images' => $this->product->images_url,
        ]);
    }
};
