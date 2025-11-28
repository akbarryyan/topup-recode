<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrepaidService extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'brand',
        'name',
        'note',
        'price_basic',
        'price_premium',
        'price_special',
        'price_basic_original',
        'price_premium_original',
        'price_special_original',
        'margin_type',
        'margin_value',
        'stock',
        'description',
        'stock_updated_at',
        'multi_trx',
        'maintenance',
        'category',
        'prepost',
        'type',
        'status',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'multi_trx' => 'boolean',
        'price_basic' => 'integer',
        'price_premium' => 'integer',
        'price_special' => 'integer',
        'price_basic_original' => 'integer',
        'price_premium_original' => 'integer',
        'price_special_original' => 'integer',
        'margin_value' => 'integer',
        'stock' => 'integer',
        'stock_updated_at' => 'datetime',
    ];

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')->where('is_active', true);
    }

    public function scopeByBrand($query, $brand)
    {
        return $query->where('brand', $brand);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function getFormattedPriceBasicAttribute()
    {
        return 'Rp ' . number_format($this->price_basic, 0, ',', '.');
    }

    public function getFormattedPricePremiumAttribute()
    {
        return 'Rp ' . number_format($this->price_premium, 0, ',', '.');
    }

    public function getFormattedPriceSpecialAttribute()
    {
        return 'Rp ' . number_format($this->price_special, 0, ',', '.');
    }

    /**
     * Calculate price with margin
     */
    public static function calculatePriceWithMargin($originalPrice, $marginType, $marginValue)
    {
        if ($marginType === 'percent') {
            return $originalPrice + ($originalPrice * $marginValue / 100);
        }
        return $originalPrice + $marginValue;
    }

    /**
     * Calculate final price based on user role
     * 
     * @param string $role User role: 'member', 'premium', 'vip', 'reseller', etc.
     * @return int Final price
     */
    public function calculateFinalPrice($role = 'member')
    {
        switch ($role) {
            case 'premium':
                return $this->price_premium ?? $this->price_basic;
            case 'vip':
            case 'reseller':
            case 'special':
                return $this->price_special ?? $this->price_premium ?? $this->price_basic;
            case 'member':
            default:
                return $this->price_basic;
        }
    }
}
