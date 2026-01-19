@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <div class="flex items-center gap-4 mb-4">
        <a href="{{ route('admin.orders.index') }}" class="text-gray-400 hover:text-gray-900 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Order Details</h1>
    </div>
    <p class="text-gray-500">Order #{{ $order->id }} - {{ $order->created_at->format('M d, Y H:i') }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Items -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50">
                <h2 class="font-bold text-gray-900 uppercase text-xs tracking-widest">Order Items</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($order->items as $item)
                    <div class="p-6 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gray-50 rounded-lg flex items-center justify-center text-primary">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-sm">{{ $item->product->title }}</h3>
                                <p class="text-xs text-gray-400">{{ $item->product->category->name }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-gray-900">₹{{ number_format($item->price, 2) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="p-6 bg-gray-50 flex justify-between items-center">
                <span class="font-bold text-gray-900">Total Amount Paid</span>
                <span class="text-2xl font-black text-primary">₹{{ number_format($order->amount, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Customer & Payment Info -->
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-900 uppercase text-xs tracking-widest mb-6">Customer Information</h2>
            <div class="flex items-center gap-4 mb-6">
                <img class="h-12 w-12 rounded-full border-2 border-primary" src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name) }}&background=1e40af&color=fff" alt="">
                <div>
                    <div class="font-bold text-gray-900 text-sm">{{ $order->user->name }}</div>
                    <div class="text-xs text-gray-400">{{ $order->user->email }}</div>
                </div>
            </div>
            <div class="pt-6 border-t border-gray-50">
                <div class="flex justify-between text-xs mb-2">
                    <span class="text-gray-400">Status</span>
                    <span class="font-bold text-green-600 uppercase">{{ $order->status }}</span>
                </div>
                <div class="flex justify-between text-xs mb-2">
                    <span class="text-gray-400">Razorpay Order</span>
                    <span class="font-mono text-gray-600">{{ $order->razorpay_order_id }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400">Razorpay Payment</span>
                    <span class="font-mono text-blue-600 font-bold">{{ $order->razorpay_payment_id }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
