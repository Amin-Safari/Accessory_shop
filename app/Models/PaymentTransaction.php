<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    protected $guarded = [];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsCompleted(string $referenceId): void
    {
        $this->update([
            'status' => 'completed',
            'reference_id' => $referenceId,
            'completed_at' => now(),
        ]);
    }

    public function markAsFailed(string $errorMessage = null): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }

    public function markAsTimedOut(): void
    {
        $this->update([
            'status' => 'timed_out',
            'error_message' => 'Payment timed out after 30 minutes',
        ]);
    }
}
