<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'payment_gateway_id',
        'code',
        'name',
        'image_url',
        'fee_merchant_flat',
        'fee_merchant_percent',
        'fee_customer_flat',
        'fee_customer_percent',
        'total_fee',
        'is_active',
        'sort_order',
        'description',
    ];

    protected $casts = [
        'fee_merchant_flat' => 'decimal:2',
        'fee_merchant_percent' => 'decimal:2',
        'fee_customer_flat' => 'decimal:2',
        'fee_customer_percent' => 'decimal:2',
        'total_fee' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Relationship to PaymentGateway
     */
    public function paymentGateway()
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    /**
     * Calculate total fee for customer based on amount
     */
    public function calculateCustomerFee($amount)
    {
        $feeFlat = $this->fee_customer_flat;
        $feePercent = ($amount * $this->fee_customer_percent) / 100;
        return $feeFlat + $feePercent;
    }

    /**
     * Calculate total amount including fee
     */
    public function calculateTotalAmount($amount)
    {
        return $amount + $this->calculateCustomerFee($amount);
    }

    /**
     * Get formatted fee display
     */
    public function getFormattedCustomerFeeAttribute()
    {
        // Use total_fee directly as it contains the actual fee from payment gateway
        if ($this->total_fee > 0) {
            return 'Rp ' . number_format((float)$this->total_fee, 0, ',', '.');
        }
        
        return 'Gratis';
    }

    /**
     * Scope for active payment methods
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific gateway
     */
    public function scopeForGateway($query, $gatewayId)
    {
        return $query->where('payment_gateway_id', $gatewayId);
    }

    /**
     * Scope ordered by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
