<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaintenanceSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_active',
        'title',
        'message',
        'button_text',
        'button_url',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function current(): self
    {
        return static::first() ?? static::create([
            'is_active' => false,
            'title' => 'Sedang Maintenance',
            'message' => 'Kami sedang melakukan pemeliharaan sistem. Mohon kembali lagi beberapa saat.',
            'button_text' => 'Hubungi Kami',
            'button_url' => 'mailto:support@example.com',
        ]);
    }
}
