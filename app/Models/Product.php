<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public const HIGHLIGHT_BADGE_OPTIONS = [
        'Most Bought',
        'New Arrivals',
        'Trending',
        'Best Seller',
        'SALE',
        'OFFER',
    ];

    public const HIGHLIGHT_BADGE_SHAPES = [
        'pill' => 'Pill (Rounded)',
        'soft_rectangle' => 'Soft Rectangle',
        'tag' => 'Tag Style (Left)',
        'circle' => 'Circle',
        'square' => 'Square',

        'banner' => 'Banner Style',
        'flag' => 'Flag Style',
        'arrow' => 'Arrow Style',
    ];



    public const HIGHLIGHT_BADGE_COLORS = [
        'golden' => 'Golden (Premium)',
        'red' => 'Red / Hot',
        'blue' => 'Blue / Info',
        'green' => 'Green / Success',
        'black' => 'Black / Dark',
        'pink' => 'Pink / Special',
        'orange' => 'Orange / Warning',
    ];

    protected $fillable = [
        'category_id',
        'user_id',
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
        'is_featured',
        'is_recent',
        'is_demo',
        'show_sale_tag',
        'sale_percentage',
        'sale_display_mode',
        'highlight_badge',
        'highlight_badge_shape',

        'highlight_badge_color',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $appends = ['image_url'];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'offer_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_downloadable' => 'boolean',
        'is_featured' => 'boolean',
        'is_recent' => 'boolean',
        'is_demo' => 'boolean',
        'show_sale_tag' => 'boolean',
        'sale_percentage' => 'integer',
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
