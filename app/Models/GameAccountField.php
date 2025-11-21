<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameAccountField extends Model
{
    protected $fillable = [
        'game_name',
        'field_key',
        'label',
        'placeholder',
        'input_type',
        'is_required',
        'helper_text',
        'sort_order',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeForGame($query, string $gameName)
    {
        return $query->where('game_name', $gameName)->orderBy('sort_order');
    }
}
