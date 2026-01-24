@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Single Orders</h1>
    <p class="text-gray-500">Manage individual product purchases and payment requests.</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-[10px] uppercase font-bold">
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Product</th>
                    <th class="px-6 py-4">Amount</th>
                    <th class="px-6 py-4">Transaction/RZP ID</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Date</th>
                    @role('Super Admin')
                    <th class="px-6 py-4 text-right">Actions</th>
                    @endrole
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($requests as $request)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <!-- ... (User, Product, Amount, ID, Status columns) ... -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-gray-900">{{ $request->user->name }}</div>
                            <div class="text-xs text-gray-400">{{ $request->user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-medium text-gray-600">{{ $request->product->title }}</div>
                        </td>
                        <td class="px-6 py-4 font-black text-primary">₹{{ number_format($request->amount, 2) }}</td>
                        <td class="px-6 py-4">
                            @if($request->razorpay_payment_id)
                                <span class="text-xs font-mono text-blue-600 font-bold">{{ $request->razorpay_payment_id }}</span>
                            @else
                                <span class="text-xs font-mono text-gray-500">{{ $request->transaction_id ?? 'N/A' }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($request->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-tight">Pending</span>
                            @elseif($request->status == 'approved' || $request->status == 'completed')
                                <span class="bg-green-100 text-green-700 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-tight">Success</span>
                            @else
                                <span class="bg-red-100 text-red-700 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-tight">Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-xs">
                            {{ $request->created_at->format('M d, Y') }}
                        </td>
                        @role('Super Admin')
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('admin.payment-requests.update', $request->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="text-[10px] border-gray-100 rounded-lg bg-gray-50 px-2 py-1 font-bold uppercase cursor-pointer focus:ring-primary">
                                    <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ $request->status == 'completed' || $request->status == 'approved' ? 'selected' : '' }}>Approve</option>
                                    <option value="rejected" {{ $request->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                                </select>
                            </form>
                        </td>
                        @endrole
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">No single orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6 bg-gray-50/50">
        {{ $requests->links() }}
    </div>
</div>
@endsection
