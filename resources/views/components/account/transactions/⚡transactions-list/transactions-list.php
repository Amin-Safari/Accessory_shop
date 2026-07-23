<?php

use App\Models\PaymentTransaction;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public $status = 'all';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 10;

    protected $queryString = ['status'];

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
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

    public function getStatusIcon($status)
    {
        return match($status) {
            'completed' => '✅',
            'pending', 'initiated' => '⏳',
            'failed', 'cancelled', 'timed_out' => '❌',
            default => '❓'
        };
    }

    public function convertToJalali($gregorianDate)
    {
        if (!$gregorianDate) {
            return '---';
        }

        return Verta::instance($gregorianDate)->format('Y/m/d - H:i');
    }

    public function convertToJalaliDate($gregorianDate)
    {
        if (!$gregorianDate) {
            return '---';
        }

        return Verta::instance($gregorianDate)->format('Y/m/d');
    }

    public function convertToJalaliTime($gregorianDate)
    {
        if (!$gregorianDate) {
            return '---';
        }

        return Verta::instance($gregorianDate)->format('H:i');
    }

    public function render()
    {
        $transactions = PaymentTransaction::where('user_id', auth()->id())
            ->when($this->status !== 'all', function ($query) {
                return $query->where('status', $this->status);
            })
            ->when($this->dateFrom, function ($query) {
                return $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                return $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->with('order')
            ->latest()
            ->paginate($this->perPage);

        return view('components.account.transactions.⚡transactions-list.transactions-list', compact('transactions'), [
            'transactions' => $transactions
        ]);
    }
};
