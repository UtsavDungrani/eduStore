<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentRequest;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PurchaseCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchased_products_cache_is_cleared_on_order_completion()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Test', 'slug' => 'test']);
        $product = Product::create([
            'category_id' => $category->id,
            'title' => 'Test Product',
            'slug' => 'test-product',
            'price' => 100,
            'file_path' => 'test.pdf'
        ]);

        // 1. Initial access - should be empty and cached
        $this->actingAs($user);
        $this->assertEmpty($user->getPurchasedProductIds());
        
        $cacheKey = "user_{$user->id}_purchased_products";
        $this->assertTrue(Cache::has($cacheKey));

        // 2. Create a pending order
        $order = Order::create([
            'user_id' => $user->id,
            'amount' => 100,
            'status' => 'pending',
            'razorpay_order_id' => 'order_123'
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'price' => 100
        ]);

        // Cache should still be there (status is pending)
        $this->assertTrue(Cache::has($cacheKey));

        // 3. Complete the order
        $order->update(['status' => 'completed']);

        // 4. Assert cache is cleared
        $this->assertFalse(Cache::has($cacheKey));

        // 5. Assert getPurchasedProductIds now returns the product
        $purchasedIds = $user->getPurchasedProductIds();
        $this->assertContains($product->id, $purchasedIds);
    }

    public function test_purchased_products_cache_is_cleared_on_payment_request_completion()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Test', 'slug' => 'test']);
        $product = Product::create([
            'category_id' => $category->id,
            'title' => 'Test Product',
            'slug' => 'test-product',
            'price' => 100,
            'file_path' => 'test.pdf'
        ]);

        $this->actingAs($user);
        $user->getPurchasedProductIds(); // prime cache
        
        $cacheKey = "user_{$user->id}_purchased_products";
        $this->assertTrue(Cache::has($cacheKey));

        // 1. Create a pending payment request
        $pr = PaymentRequest::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'amount' => 100,
            'status' => 'pending',
            'transaction_id' => 'tx_123'
        ]);

        $this->assertTrue(Cache::has($cacheKey));

        // 2. Approve/Complete the payment request
        $pr->update(['status' => 'approved']);

        // 3. Assert cache is cleared
        $this->assertFalse(Cache::has($cacheKey));
        $this->assertContains($product->id, $user->getPurchasedProductIds());
    }
}
