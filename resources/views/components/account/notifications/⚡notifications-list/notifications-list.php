<?php

use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public $filter = 'all';
    public $type = 'all';

    protected $queryString = ['filter', 'type'];

    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function updatedType()
    {
        $this->resetPage();
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);

        if ($notification && $notification->user_id === auth()->id()) {
            $notification->markAsRead();
            $this->dispatch('notification-read');
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->notifications()
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        session()->flash('all_read', 'همه اعلان‌ها به عنوان خوانده شده علامت‌گذاری شدند.');
        $this->dispatch('all-notifications-read');
    }

    public function deleteNotification($notificationId)
    {
        $notification = Notification::find($notificationId);

        if ($notification && $notification->user_id === auth()->id()) {
            $notification->delete();
            $this->dispatch('notification-deleted');
        }
    }

    public function deleteAllRead()
    {
        auth()->user()->notifications()
            ->where('is_read', true)
            ->delete();

        session()->flash('all_read_deleted', 'همه اعلان‌های خوانده شده حذف شدند.');
        $this->dispatch('read-notifications-deleted');
    }

    public function convertToJalali($date)
    {
        if (!$date) return '';
        return Verta::instance($date);
    }

    public function getTimeAgo($date)
    {
        if (!$date) return '';

        $jalali = $this->convertToJalali($date);
        $now = Verta::now();

        $diffMinutes = $jalali->diffInMinutes($now);
        $diffHours = $jalali->diffInHours($now);
        $diffDays = $jalali->diffInDays($now);

        if ($diffMinutes < 1) {
            return 'همین الان';
        } elseif ($diffMinutes < 60) {
            return $diffMinutes . ' دقیقه پیش';
        } elseif ($diffHours < 24) {
            return $diffHours . ' ساعت پیش';
        } elseif ($diffDays < 7) {
            return $diffDays . ' روز پیش';
        } else {
            return $jalali->format('Y/m/d');
        }
    }

    public function getNotificationIcon($type)
    {
        return match($type) {
            'order_status' => '📦',
            'shipment' => '🚚',
            'payment' => '💳',
            'system' => '⚙️',
            'promotion' => '🎉',
            default => '📌'
        };
    }

    public function getNotificationColor($type)
    {
        return match($type) {
            'order_status' => 'border-primary bg-primary/5',
            'shipment' => 'border-info bg-info/5',
            'payment' => 'border-success bg-success/5',
            'system' => 'border-warning bg-warning/5',
            'promotion' => 'border-error bg-error/5',
            default => 'border-base-300 bg-base-200'
        };
    }

    public function render()
    {
        $notifications = auth()->user()->notifications()
            ->when($this->filter === 'unread', function ($query) {
                return $query->where('is_read', false);
            })
            ->when($this->filter === 'read', function ($query) {
                return $query->where('is_read', true);
            })
            ->when($this->type !== 'all', function ($query) {
                return $query->where('type', $this->type);
            })
            ->latest()
            ->paginate(15);

        $unreadCount = auth()->user()->notifications()->where('is_read', false)->count();

        return view('components.account.notifications.⚡notifications-list.notifications-list', compact('notifications', 'unreadCount'), [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }
};
