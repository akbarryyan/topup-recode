<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TopUpTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'merchant_order_id',
        'reference',
        'amount',
        'fee',
        'total_amount',
        'payment_method_id',
        'payment_url',
        'va_number',
        'qr_string',
        'status',
        'expired_at',
        'paid_at',
        'notes',
        'callback_data',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
        'callback_data' => 'array',
    ];

    /**
     * Get the user that owns the transaction
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payment method used
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * Scope for pending transactions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for paid transactions
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Check if transaction is expired
     */
    public function isExpired(): bool
    {
        return $this->expired_at && $this->expired_at->isPast() && $this->status === 'pending';
    }

    /**
     * Mark transaction as paid
     */
    public function markAsPaid()
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    /**
     * Mark transaction as failed
     */
    public function markAsFailed($notes = null)
    {
        $this->update([
            'status' => 'failed',
            'notes' => $notes,
        ]);
    }

    /**
     * Mark transaction as expired
     */
    public function markAsExpired()
    {
        $this->update([
            'status' => 'expired',
        ]);
    }
}
