<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'merchant_code',
        'api_key',
        'private_key',
        'environment',
        'is_active',
        'callback_url',
        'return_url',
        'icon_url',
        'fee_flat',
        'fee_percent',
        'type',
        'group',
        'additional_config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'additional_config' => 'array',
        'fee_flat' => 'decimal:2',
        'fee_percent' => 'decimal:2',
    ];

    /**
     * Encrypt API Key before saving
     */
    public function setApiKeyAttribute($value)
    {
        if ($value) {
            $this->attributes['api_key'] = Crypt::encryptString($value);
        }
    }

    /**
     * Decrypt API Key when retrieving
     */
    public function getApiKeyAttribute($value)
    {
        if (!$value) {
            return null;
        }
        
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    /**
     * Encrypt Private Key before saving
     */
    public function setPrivateKeyAttribute($value)
    {
        if ($value) {
            $this->attributes['private_key'] = Crypt::encryptString($value);
        }
    }

    /**
     * Decrypt Private Key when retrieving
     */
    public function getPrivateKeyAttribute($value)
    {
        if (!$value) {
            return null;
        }
        
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    /**
     * Get masked API Key for display
     */
    public function getMaskedApiKeyAttribute()
    {
        $apiKey = $this->api_key;
        if (!$apiKey) {
            return '-';
        }
        
        if (strlen($apiKey) <= 8) {
            return str_repeat('*', strlen($apiKey));
        }
        return substr($apiKey, 0, 4) . str_repeat('*', strlen($apiKey) - 8) . substr($apiKey, -4);
    }

    /**
     * Get masked Private Key for display
     */
    public function getMaskedPrivateKeyAttribute()
    {
        $privateKey = $this->private_key;
        if (!$privateKey) {
            return '-';
        }
        
        if (strlen($privateKey) <= 8) {
            return str_repeat('*', strlen($privateKey));
        }
        return substr($privateKey, 0, 4) . str_repeat('*', strlen($privateKey) - 8) . substr($privateKey, -4);
    }

    /**
     * Get environment badge color
     */
    public function getEnvironmentBadgeAttribute()
    {
        return $this->environment === 'production' ? 'success' : 'warning';
    }

    /**
     * Scope query to only active gateways
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get gateway by code
     */
    public static function getByCode($code)
    {
        return self::where('code', $code)->where('is_active', true)->first();
    }

    /**
     * Relationship to PaymentMethods
     */
    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    /**
     * Get active payment methods
     */
    public function activePaymentMethods()
    {
        return $this->hasMany(PaymentMethod::class)->where('is_active', true)->orderBy('sort_order')->orderBy('name');
    }
}