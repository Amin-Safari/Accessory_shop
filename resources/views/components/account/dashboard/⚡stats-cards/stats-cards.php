<?php

use Livewire\Component;
use App\Models\Order;

new class extends Component
{
    public $totalOrders = 0;
    public $paidOrders = 0;
    public $pendingOrders = 0;
    public $cancelledOrders = 0;

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $orders = Order::where('user_id', auth()->id());

        $this->totalOrders = $orders->count();
        $this->paidOrders = $orders->clone()->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])->count();
        $this->pendingOrders = $orders->clone()->whereIn('status', ['pending', 'awaiting_payment'])->count();
        $this->cancelledOrders = $orders->clone()->whereIn('status', ['cancelled', 'payment_failed'])->count();
    }
};
