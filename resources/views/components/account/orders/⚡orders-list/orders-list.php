<?php

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public $status = 'all';
    public $search = '';

    protected $queryString = ['status', 'search'];

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function getStatusLabel($status)
    {
        return match($status) {
            'pending' => 'در انتظار',
            'awaiting_payment' => 'در انتظار پرداخت',
            'paid' => 'پرداخت شده',
            'processing' => 'در حال پردازش',
            'shipped' => 'ارسال شده',
            'delivered' => 'تحویل شده',
            'cancelled' => 'لغو شده',
            'payment_failed' => 'پرداخت ناموفق',
            default => $status
        };
    }

    public function getStatusColor($status)
    {
        return match($status) {
            'pending', 'awaiting_payment' => 'badge-warning',
            'paid', 'processing' => 'badge-info',
            'shipped' => 'badge-primary',
            'delivered' => 'badge-success',
            'cancelled', 'payment_failed' => 'badge-error',
            default => 'badge-ghost'
        };
    }

    public function render()
    {
        $orders = Order::where('user_id', auth()->id())
            ->when($this->status !== 'all', function ($query) {
                return $query->where('status', $this->status);
            })
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('order_number', 'like', '%' . $this->search . '%')
                        ->orWhere('tracking_code', 'like', '%' . $this->search . '%');
                });
            })
            ->with(['items.product', 'paymentTransactions'])
            ->latest()
            ->paginate(10);

        return view('components.account.orders.⚡orders-list.orders-list', compact('orders'), [
            'orders' => $orders
        ]);
    }

};
