@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Site Settings & Branding</h1>
    <p class="text-gray-500">Configure global site values and appearance.</p>
</div>

<div class="max-w-4xl">
    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-globe text-primary"></i> General Settings
                </h3>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Site Name</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'Digital Store' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Primary Brand Color</label>
                    <div class="flex items-center gap-4">
                        <input type="color" name="brand_color" value="{{ $settings['brand_color'] ?? '#1e40af' }}" class="w-12 h-12 rounded-lg cursor-pointer border-none p-0">
                        <input type="text" value="{{ $settings['brand_color'] ?? '#1e40af' }}" readonly class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm font-mono">
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Support Email</label>
                    <input type="email" name="support_email" value="{{ $settings['support_email'] ?? 'support@example.com' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-credit-card text-green-500"></i> Payment Configuration
                </h3>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6 border-b border-gray-50">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Active Payment Gateway</label>
                    <select name="active_payment_method" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary">
                        <option value="razorpay" {{ ($settings['active_payment_method'] ?? '') == 'razorpay' ? 'selected' : '' }}>Razorpay Only</option>
                        <option value="manual" {{ ($settings['active_payment_method'] ?? '') == 'manual' ? 'selected' : '' }}>Manual (UPI/Bank) Only</option>
                        <option value="both" {{ ($settings['active_payment_method'] ?? '') == 'both' ? 'selected' : '' }}>Both Razorpay & Manual</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">UPI Intent (Mobile)</label>
                    <div class="flex items-center gap-4 mt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="upi_apps_visibility" value="show" {{ ($settings['upi_apps_visibility'] ?? 'show') == 'show' ? 'checked' : '' }} class="text-primary focus:ring-primary">
                            <span class="text-sm font-medium text-gray-600">Show Installed Apps</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="upi_apps_visibility" value="hide" {{ ($settings['upi_apps_visibility'] ?? '') == 'hide' ? 'checked' : '' }} class="text-primary focus:ring-primary">
                            <span class="text-sm font-medium text-gray-600">Hide</span>
                        </label>
                    </div>
                </div>
            </div>
            
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-desktop text-purple-500"></i> User Interface Settings
                </h3>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Product Slider Auto-scroll</label>
                    <div class="flex items-center gap-4 mt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="products_auto_scroll" value="1" {{ ($settings['products_auto_scroll'] ?? '1') == '1' ? 'checked' : '' }} class="text-primary focus:ring-primary">
                            <span class="text-sm font-medium text-gray-600">Enabled</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="products_auto_scroll" value="0" {{ ($settings['products_auto_scroll'] ?? '') == '0' ? 'checked' : '' }} class="text-primary focus:ring-primary">
                            <span class="text-sm font-medium text-gray-600">Disabled</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                <h3 class="font-bold text-gray-900 flex items-center gap-2 text-sm uppercase tracking-wide">
                     Manual Payment Details
                </h3>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">UPI ID</label>
                    <input type="text" name="upi_id" value="{{ $settings['upi_id'] ?? '' }}" placeholder="yourname@bank" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">UPI Merchant Name</label>
                    <input type="text" name="upi_name" value="{{ $settings['upi_name'] ?? '' }}" placeholder="Store Name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">QR Code URL (Optional)</label>
                    <input type="text" name="qr_code_url" value="{{ $settings['qr_code_url'] ?? '' }}" placeholder="https://..." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary">
                    <p class="mt-2 text-xs text-gray-400">Link to an image of your payment QR code.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-primary text-white px-10 py-4 rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg">Save All Settings</button>
        </div>
    </form>
</div>
@endsection
