<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDemoImage extends Model
{
    protected $fillable = [
        'product_id',
        'image_path',
        'order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageUrlAttribute()
    {
        return route('product.demo.serve', ['demoImage' => $this->id]);
    }
}
