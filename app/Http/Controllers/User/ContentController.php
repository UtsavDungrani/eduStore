<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\AccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ContentController extends Controller
{
    public function show(Product $product)
    {
        // Check if product is free or user has an approved payment request (Manual/Razorpay)
        $hasAccess = $product->price == 0 || $product->is_demo || 
                    auth()->user()->hasRole('Super Admin') ||
                    \App\Models\PaymentRequest::where('user_id', auth()->id())
                        ->where('product_id', $product->id)
                        ->where(function($q) {
                            $q->where('status', 'approved')->orWhere('status', 'completed');
                        })
                        ->exists() ||
                    \App\Models\OrderItem::whereHas('order', function($q) {
                        $q->where('user_id', auth()->id())
                          ->where('status', 'completed');
                    })->where('product_id', $product->id)->exists();

        if (!$hasAccess) {
            abort(403, 'You do not have access to this content. Please purchase it first.');
        }

        $signedUrl = URL::temporarySignedRoute(
            'content.stream', 
            now()->addMinutes(30), 
            [
                'product' => $product->id,
                'uid' => auth()->id()
            ]
        );

        return view('user.products.viewer', compact('product', 'signedUrl'));
    }

    public function demoView(Product $product)
    {
        // Allow access to demo view for everyone
        // We still sign the URL for the stream to valid for a short time
        $signedUrl = URL::temporarySignedRoute(
            'content.demo.stream', 
            now()->addMinutes(30), 
            [
                'product' => $product->id,
                // No user ID binding for public demo stream to keep it simple, 
                // or bind to session/IP if needed. For now, we trust the demo stream endpoint.
            ]
        );

        $isDemo = true;
        // Demo limit: 5 pages
        $demoLimit = 5;

        return view('user.products.viewer', compact('product', 'signedUrl', 'isDemo', 'demoLimit'));
    }

    public function stream(Request $request, Product $product)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired signature.');
        }

        if (!Storage::disk('private')->exists($product->file_path)) {
            abort(404);
        }

        // Log the view
        AccessLog::create([
            'user_id' => $request->uid ?? auth()->id(),
            'product_id' => $product->id,
            'action_type' => 'view',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return Storage::disk('private')->response($product->file_path);
    }

    public function download(Request $request, Product $product)
    {
        $user = auth()->user();
        
        // Authorization logic:
        // - Super Admin can always download
        // - Users with 'download content' permission can always download
        // - Regular users can download if it's a demo
        // - Regular users can download if they purchased it AND it's marked as downloadable
        // - Regular users can download if it's free AND it's marked as downloadable
        
        $canDownload = $user->hasRole('Super Admin') || 
                       $user->can('download content') ||
                       $product->is_demo ||
                       ($user->hasPurchased($product->id) && $product->is_downloadable) ||
                       ($product->price == 0 && $product->is_downloadable);

        if (!$canDownload) {
            abort(403, 'Downloading this content is restricted.');
        }

        // Log the download
        AccessLog::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'action_type' => 'download',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);


        return Storage::disk('private')->download($product->file_path, $product->title . '.' . pathinfo($product->file_path, PATHINFO_EXTENSION));
    }

    /**
     * Serve banner images from storage
     * This avoids the need for symlinks on shared hosting
     */
    public function serveBanner(\App\Models\Banner $banner)
    {
        if (!Storage::disk('public')->exists($banner->image_path)) {
            abort(404, 'Banner image not found');
        }

        $path = Storage::disk('public')->path($banner->image_path);
        $mimeType = Storage::disk('public')->mimeType($banner->image_path);

        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000', // Cache for 1 year
        ]);
    }

    /**
     * Serve product cover images from storage
     * This avoids the need for symlinks on shared hosting
     */
    public function serveCover(Product $product)
    {
        if (!$product->image_path || !Storage::disk('public')->exists($product->image_path)) {
            abort(404, 'Product cover image not found');
        }

        $path = Storage::disk('public')->path($product->image_path);
        $mimeType = Storage::disk('public')->mimeType($product->image_path);

        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000', // Cache for 1 year
        ]);
    }

    /**
     * Serve product file for demo purposes (Buy Preview)
     * CAUTION: This serves the full file. Frontend should only restrict display.
     */
    public function demoStream(Request $request, Product $product)
    {
        // Only for priced products. Free products have their own flow.
        if ($product->price <= 0) {
            abort(404);
        }

        if (!Storage::disk('private')->exists($product->file_path)) {
            abort(404);
        }

        // Return the file for PDF.js to consume
        return Storage::disk('private')->response($product->file_path);
    }
}
