<?php

use App\Models\PaymentTransaction;
use Livewire\Component;

new class extends Component
{
    public PaymentTransaction $transaction;

    public function convertToJalali($date)
    {
        if (!$date) return '---';
        return Verta::instance($date)->format('Y/m/d H:i:s');
    }

    public function getStatusLabel($status)
    {
        return match($status) {
            'initiated' => 'شروع شده',
            'pending' => 'در انتظار',
            'completed' => 'موفق',
            'failed' => 'ناموفق',
            'cancelled' => 'لغو شده',
            'timed_out' => 'منقضی شده',
            default => $status
        };
    }

    public function getStatusColor($status)
    {
        return match($status) {
            'completed' => 'badge-success',
            'pending', 'initiated' => 'badge-warning',
            'failed', 'cancelled', 'timed_out' => 'badge-error',
            default => 'badge-ghost'
        };
    }

};
