<?php

use App\Models\Order;
use Livewire\Component;

new class extends Component
{
    public $orders = [];

    public function mount()
    {
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $this->orders = Order::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'awaiting_payment', 'processing'])
            ->with(['items.product', 'paymentTransactions'])
            ->latest()
            ->take(5)
            ->get();
    }
};
