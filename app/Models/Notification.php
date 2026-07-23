<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id', 'type', 'title', 'message',
        'data', 'icon', 'color', 'is_read', 'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Helpers
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null
        ]);
    }

    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'order_status' => 'وضعیت سفارش',
            'shipment' => 'ارسال و تحویل',
            'payment' => 'پرداخت',
            'system' => 'سیستمی',
            'promotion' => 'تخفیف و پیشنهاد',
            default => 'عمومی'
        };
    }

    public function getTypeIconAttribute()
    {
        return match($this->type) {
            'order_status' => '📦',
            'shipment' => '🚚',
            'payment' => '💳',
            'system' => '⚙️',
            'promotion' => '🎉',
            default => '📌'
        };
    }

    public function getTypeColorAttribute()
    {
        return match($this->type) {
            'order_status' => 'primary',
            'shipment' => 'info',
            'payment' => 'success',
            'system' => 'warning',
            'promotion' => 'error',
            default => 'ghost'
        };
    }
}
