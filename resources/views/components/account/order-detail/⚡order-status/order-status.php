<?php

use App\Models\Order;
use Livewire\Component;

new class extends Component
{
    public Order $order;

    public function getStatusStep($status)
    {
        $steps = [
            'pending' => 0,
            'awaiting_payment' => 1,
            'paid' => 2,
            'processing' => 3,
            'shipped' => 4,
            'delivered' => 5,
        ];

        return $steps[$status] ?? 0;
    }

    public function getCurrentStep()
    {
        if (in_array($this->order->status, ['cancelled', 'payment_failed'])) {
            return -1; // Error state
        }
        return $this->getStatusStep($this->order->status);
    }

    public function render()
    {
        return view('components.account.order-detail.⚡order-status.order-status', [
            'currentStep' => $this->getCurrentStep(),
            'isCancelled' => in_array($this->order->status, ['cancelled', 'payment_failed'])
        ]);
    }
};
