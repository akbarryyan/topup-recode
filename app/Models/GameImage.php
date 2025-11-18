<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameImage extends Model
{
    protected $fillable = [
        'game_name',
        'image',
    ];

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image && file_exists(public_path('storage/game-images/' . $this->image))) {
            return asset('storage/game-images/' . $this->image);
        }
        return asset('assets/img/game-placeholder.svg');
    }

    /**
     * Get all services for this game
     */
    public function services()
    {
        return $this->hasMany(GameService::class, 'game', 'game_name');
    }
}
