<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'price',
        'original_price',
        'offer_price',
        'sale_tag',
        'file_path',
        'image_path',
        'is_active',
        'is_downloadable',
        'is_demo',
    ];

    protected $appends = ['image_url'];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'offer_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_downloadable' => 'boolean',
        'is_demo' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function accessLogs()
    {
        return $this->hasMany(AccessLog::class);
    }

    public function getSellingPriceAttribute()
    {
        return ($this->offer_price && $this->offer_price > 0) ? $this->offer_price : $this->price;
    }

    public function getOnSaleAttribute()
    {
        return $this->offer_price && $this->offer_price > 0;
    }

    /**
     * Get the full URL for the product cover image
     * Uses route-based serving to avoid symlink requirements
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return route('product.cover.serve', ['product' => $this->id]) . '?v=' . $this->updated_at->timestamp;
        }
        return null;
    }
}
