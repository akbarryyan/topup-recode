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
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    /**
     * Get the user that owns the transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get formatted balance
     */
    public function getFormattedBalanceAttribute()
    {
        return 'Rp ' . number_format($this->balance, 0, ',', '.');
    }
}
