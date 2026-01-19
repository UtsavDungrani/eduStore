<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($order) {
            if ($order->isDirty('status') && $order->status === 'completed') {
                $order->user->clearPurchasedProductsCache();
            }
        });
    }

    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
