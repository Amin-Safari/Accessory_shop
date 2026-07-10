<?php

use Livewire\Component;

new class extends Component
{
    public $products = [];

    public function mount($products = [])
    {
        $this->products = $products;
    }
};
