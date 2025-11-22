<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VipResellerSetting extends Model
{
    protected $fillable = [
        'api_url',
        'api_id',
        'api_key',
        'sign',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function current(): ?self
    {
        return cache()->remember('vip_reseller_setting', 60, function () {
            return self::where('is_active', true)->latest()->first();
        });
    }

    public static function refreshCache(): void
    {
        cache()->forget('vip_reseller_setting');
        self::current();
    }
}
