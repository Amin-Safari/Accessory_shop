<?php

namespace App\Livewire\Account;

use Livewire\Component;
use App\Models\Order;

class OrderDetail extends Component
{
    public $order;

    public function mount(Order $order)
    {
        // Check if order belongs to authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        $this->order = $order->load([
            'items.product',
            'paymentTransactions'
        ]);
    }

    public function render()
    {
        return view('livewire.account.order-detail')
            ->layout('layouts.account', [
                'title' => 'جزئیات سفارش ' . $this->order->order_number
            ]);
    }
}
