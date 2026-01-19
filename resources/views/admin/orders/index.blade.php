@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Cart Orders</h1>
    <p class="text-gray-500">View multi-product purchases made via Razorpay.</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-[10px] uppercase font-bold">
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Items</th>
                    <th class="px-6 py-4">Amount</th>
                    <th class="px-6 py-4">RZP Payment ID</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-gray-900">{{ $order->user->name }}</div>
                            <div class="text-xs text-gray-400">{{ $order->user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-medium text-gray-600">{{ $order->items->count() }} Items</div>
                            <div class="text-[10px] text-gray-400 truncate max-w-[150px]">
                                {{ $order->items->map(fn($i) => $i->product->title)->implode(', ') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 font-black text-primary">₹{{ number_format($order->amount, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-mono text-blue-600 font-bold">{{ $order->razorpay_payment_id ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($order->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-tight">Pending</span>
                            @elseif($order->status == 'completed')
                                <span class="bg-green-100 text-green-700 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-tight">Completed</span>
                            @else
                                <span class="bg-red-100 text-red-700 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-tight">Failed</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-primary hover:text-blue-700 font-bold text-xs">View Details</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">No cart orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6 bg-gray-50/50">
        {{ $orders->links() }}
    </div>
</div>
@endsection
