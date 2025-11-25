<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameService extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'game',
        'name',
        'description',
        'price_basic',
        'price_premium',
        'price_special',
        'price_basic_original',
        'price_premium_original',
        'price_special_original',
        'margin_type',
        'margin_value',
        'server',
        'status',
        'stock',
        'stock_updated_at',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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

    public function scopeByGame($query, $game)
    {
        return $query->where('game', $game);
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
     * Get game image relationship
     */
    public function gameImage()
    {
        return $this->belongsTo(GameImage::class, 'game', 'game_name');
    }

    /**
     * Get image URL with fallback
     */
    public function getImageUrlAttribute()
    {
        if ($this->gameImage && $this->gameImage->image) {
            return $this->gameImage->image_url;
        }
        // Fallback to placeholder
        return asset('assets/img/game-placeholder.svg');
    }

    /**
     * Check if game has image
     */
    public function getHasImageAttribute()
    {
        return $this->gameImage && $this->gameImage->image;
    }

    /**
     * Calculate final price based on user role
     */
    public function calculateFinalPrice($role = 'member')
    {
        return match($role) {
            'platinum' => $this->price_special > 0 ? $this->price_special : $this->price_basic,
            'gold' => $this->price_premium > 0 ? $this->price_premium : $this->price_basic,
            default => $this->price_basic,
        };
    }
}