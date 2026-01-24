<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'user_id');
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all product IDs purchased by the user.
     */
    public function getPurchasedProductIds()
    {
        return cache()->remember("user_{$this->id}_purchased_products", 3600, function () {
            $singleProductIds = \App\Models\PaymentRequest::where('user_id', $this->id)
                ->whereIn('status', ['completed', 'approved'])
                ->pluck('product_id');

            $cartProductIds = \App\Models\OrderItem::whereHas('order', function ($query) {
                    $query->where('user_id', $this->id)->where('status', 'completed');
                })
                ->pluck('product_id');

            return $singleProductIds->merge($cartProductIds)->unique()->toArray();
        });
    }

    /**
     * Check if the user has purchased a specific product.
     */
    public function hasPurchased($productId)
    {
        return in_array($productId, $this->getPurchasedProductIds());
    }

    /**
     * Clear the purchased products cache for this user.
     */
    public function clearPurchasedProductsCache()
    {
        cache()->forget("user_{$this->id}_purchased_products");
    }
}
