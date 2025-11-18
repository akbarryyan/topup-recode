<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandImage extends Model
{
    protected $fillable = [
        'brand_name',
        'image',
    ];

    /**
     * Get all prepaid services for this brand
     */
    public function prepaidServices()
    {
        return $this->hasMany(PrepaidService::class, 'brand', 'brand_name');
    }

    /**
     * Get image URL with fallback to placeholder
     */
    public function getImageUrlAttribute()
    {
        if ($this->image && file_exists(public_path('storage/brand-images/' . $this->image))) {
            return asset('storage/brand-images/' . $this->image);
        }
        return asset('storage/brand-images/brand-placeholder.svg');
    }
}
