<?php

use Livewire\Component;
use App\Models\Product;

new class extends Component
{
    public Product $product;
    public string $activeTab = 'description';

    public function mount(Product $product)
    {
        $this->product = $product;
    }
};
