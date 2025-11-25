<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrepaidTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'trxid',
        'user_id',
        'service_code',
        'service_name',
        'data_no',
        'status',
        'price',
        'balance',
        'note',
        // Payment Fields
        'payment_method_id',
        'payment_method_code',
        'payment_amount',
        'payment_fee',
        'payment_url',
        'payment_reference',
        'va_number',
        'qr_string',
        'email',
        'whatsapp',
        'payment_status',
        'paid_at',
        'expired_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'balance' => 'decimal:2',
        'payment_amount' => 'decimal:2',
        'payment_fee' => 'decimal:2',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    /**
     * Get the user that owns the transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payment method
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'waiting' => 'warning',
            'processing' => 'info',
            'success' => 'success',
            'failed' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format((float) $this->price, 0, ',', '.');
    }

    /**
     * Get formatted balance
     */
    public function getFormattedBalanceAttribute()
    {
        return 'Rp ' . number_format((float) $this->balance, 0, ',', '.');
    }

    /**
     * Scope for successful transactions only
     */
    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope for today's transactions
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope for this week's transactions
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope for this month's transactions
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }
}
