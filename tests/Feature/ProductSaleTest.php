<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class ProductSaleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin role and user
        // Create admin role and user
        $role = Role::create(['name' => 'Super Admin']);
        $this->user = User::factory()->create();
        $this->user->assignRole('Super Admin');
        
        Storage::fake('private');
    }

    public function test_admin_can_create_product_with_sale_fields()
    {
        $category = Category::create(['name' => 'Test Category', 'slug' => 'test-category']);

        $response = $this->actingAs($this->user)->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'title' => 'Sale Product',
            'description' => 'Description',
            'price' => 100,
            'original_price' => 150,
            'offer_price' => 90,
            'sale_tag' => 'BEST SELLER',
            'product_file' => UploadedFile::fake()->create('document.pdf', 100),
            'is_active' => 1,
            'is_downloadable' => 1,
        ]);

        if ($response->status() !== 302) {
            dump($response->json());
        }
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'title' => 'Sale Product',
            'price' => 100,
            'original_price' => 150,
            'offer_price' => 90,
            'sale_tag' => 'BEST SELLER',
        ]);
    }

    public function test_admin_can_update_product_sale_fields()
    {
        $category = Category::create(['name' => 'Test Category', 'slug' => 'test-category']);
        $product = Product::create([
            'category_id' => $category->id,
            'title' => 'Old Product',
            'slug' => 'old-product',
            'price' => 50,
            'file_path' => 'products/old.pdf',
        ]);

        $response = $this->actingAs($this->user)->patch(route('admin.products.update', $product), [
            'category_id' => $category->id,
            'title' => 'Old Product',
            'price' => 50,
            'original_price' => 80,
            'offer_price' => 45,
            'sale_tag' => 'CLEARANCE',
        ]);

        if ($response->status() !== 302) {
            dump($response->json());
        }
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'original_price' => 80,
            'offer_price' => 45,
            'sale_tag' => 'CLEARANCE',
        ]);
    }
}
