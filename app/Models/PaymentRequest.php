<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($paymentRequest) {
            if ($paymentRequest->isDirty('status') && in_array($paymentRequest->status, ['completed', 'approved'])) {
                $paymentRequest->user->clearPurchasedProductsCache();
            }
        });
    }

    protected $fillable = [
        'user_id', 
        'product_id', 
        'amount', 
        'transaction_id', 
        'payment_proof', 
        'status', 
        'admin_note',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
