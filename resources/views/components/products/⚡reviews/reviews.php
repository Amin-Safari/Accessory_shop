<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Product;

class Reviews extends Component
{
    public Product $product;
    public $reviews = [];

    public function mount(Product $product, $reviews = [])
    {
        $this->product = $product;
        $this->reviews = $reviews;
    }

}
