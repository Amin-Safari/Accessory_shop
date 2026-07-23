<?php

use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component {
    public array $items = [];
    public function mount(): void
    {
        $this->loadCart();
    }

    #[On('cart-updated')]
    public function loadCart(): void
    {

        $this->items = session('cart', []);
    }

    public function getCountProperty(): int
    {
        return collect($this->items)->sum('qty');
    }

    public function getTotalProperty(): int
    {
        return collect($this->items)
            ->sum(fn($item) => $item['qty'] * ($item['price'] * (1 - (0.01 * $item['discount']))));
    }

    public function remove($id): void
    {
        $cart = session('cart', []);

        unset($cart[$id]);

        session()->put('cart', $cart);

        $this->loadCart();

        $this->dispatch('cart-updated');
    }
};
