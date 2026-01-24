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
Route::get('/intro', function () {
    return view('user.intro');
})->name('intro');
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
Route::middleware(['auth', 'role:Super Admin|Instructor'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');
    
    // Analytics
    Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics');

    // Instructor Payouts
    Route::get('/instructors/payouts', [\App\Http\Controllers\Admin\PayoutController::class, 'index'])->name('instructors.payouts');
    
    Route::resource('products', AdminProduct::class);
    Route::resource('orders', AdminOrder::class)->only(['index', 'show']);
    Route::resource('payment-requests', \App\Http\Controllers\Admin\PaymentRequestController::class)->only(['index', 'show', 'update']);

    // Super Admin Only
    Route::middleware('role:Super Admin')->group(function () {
        Route::get('/logs', [AdminDashboard::class, 'index'])->name('logs');
        
        // Featured Content Management
        Route::get('/featured', [\App\Http\Controllers\Admin\FeaturedContentController::class, 'index'])->name('featured.index');
        Route::post('/featured', [\App\Http\Controllers\Admin\FeaturedContentController::class, 'update'])->name('featured.update');

        // Recently Added Management
        Route::get('/recent', [\App\Http\Controllers\Admin\RecentContentController::class, 'index'])->name('recent.index');
        Route::post('/recent', [\App\Http\Controllers\Admin\RecentContentController::class, 'update'])->name('recent.update');

        Route::resource('users', AdminUser::class);
        Route::resource('categories', AdminCategory::class);
        Route::resource('banners', AdminBanner::class);
        Route::post('banners/reorder', [AdminBanner::class, 'reorder'])->name('banners.reorder');

        // Setting Management
        Route::get('settings', [AdminSetting::class, 'index'])->name('settings.index');
        Route::post('settings', [AdminSetting::class, 'update'])->name('settings.update');
    });
});

require __DIR__.'/auth.php';
