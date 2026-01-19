<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\ProductController as AdminProduct;
use App\Http\Controllers\Admin\CategoryController as AdminCategory;
use App\Http\Controllers\Admin\BannerController as AdminBanner;

use App\Http\Controllers\Admin\OrderController as AdminOrder;
use App\Http\Controllers\Admin\SettingController as AdminSetting;
use App\Http\Controllers\User\ProductController as UserProduct;
use App\Http\Controllers\User\ContentController as UserContent;
use App\Http\Controllers\User\PaymentController as UserPayment;
use App\Http\Controllers\User\CartController as UserCart;
use App\Http\Controllers\User\RazorpayController;
use Illuminate\Support\Facades\Route;

// User Routes
Route::get('/', [UserProduct::class, 'home'])->name('home');
Route::get('/products', [UserProduct::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [UserProduct::class, 'show'])->name('products.show');

// Authenticated User Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');

    Route::get('/view/{product}', [UserContent::class, 'show'])->name('content.view');
    Route::get('/download/{product}', [UserContent::class, 'download'])->name('content.download');

    // Payments
    Route::post('/products/{product}/pay', [UserPayment::class, 'store'])->name('payments.store');
    Route::post('/razorpay/order/{product}', [RazorpayController::class, 'createOrder'])->name('razorpay.order');
    Route::post('/razorpay/cart-order', [RazorpayController::class, 'createCartOrder'])->name('razorpay.cart.order');
    Route::post('/razorpay/verify', [RazorpayController::class, 'verifyPayment'])->name('razorpay.verify');

    // Cart & Library
    Route::get('/cart', [UserCart::class, 'index'])->name('cart');
    Route::get('/library', [UserCart::class, 'library'])->name('library');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Private Content Streaming (Protected by Signed URL)
Route::get('/stream/{product}', [UserContent::class, 'stream'])->name('content.stream');

// Banner & Product Cover Image Serving (No symlink required)
Route::get('/banner/{banner}', [UserContent::class, 'serveBanner'])->name('banner.serve');
Route::get('/product-cover/{product}', [UserContent::class, 'serveCover'])->name('product.cover.serve');

// Admin Routes (using roles)
Route::middleware(['auth', 'role:Super Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/logs', [AdminDashboard::class, 'logs'])->name('logs');
    
    Route::resource('users', AdminUser::class);
    Route::resource('products', AdminProduct::class);
    Route::resource('categories', AdminCategory::class);
    Route::resource('banners', AdminBanner::class);
    Route::post('banners/reorder', [AdminBanner::class, 'reorder'])->name('banners.reorder');



    Route::resource('orders', AdminOrder::class)->only(['index', 'show']);

    // Setting Management
    Route::get('settings', [AdminSetting::class, 'index'])->name('settings.index');
    Route::post('settings', [AdminSetting::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
