<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['image_path', 'title', 'link', 'is_active', 'order'];
    
    protected $appends = ['image_url'];
    
    /**
     * Get the full URL for the banner image
     * Uses route-based serving to avoid symlink requirements
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return route('banner.serve', ['banner' => $this->id]);
        }
        return null;
    }
}
