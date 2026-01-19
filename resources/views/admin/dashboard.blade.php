@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Dashboard Overview</h1>
    <p class="text-gray-500">Welcome back, Admin. Here's what's happening today.</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="text-blue-500 mb-2"><i class="fas fa-users text-2xl"></i></div>
        <div class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Users</div>
        <div class="text-2xl font-black text-gray-900">{{ $stats['users'] }}</div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="text-purple-500 mb-2"><i class="fas fa-box text-2xl"></i></div>
        <div class="text-gray-500 text-xs font-bold uppercase tracking-wider">Products</div>
        <div class="text-2xl font-black text-gray-900">{{ $stats['products'] }}</div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="text-orange-500 mb-2"><i class="fas fa-tags text-2xl"></i></div>
        <div class="text-gray-500 text-xs font-bold uppercase tracking-wider">Categories</div>
        <div class="text-2xl font-black text-gray-900">{{ $stats['categories'] }}</div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="text-green-500 mb-2"><i class="fas fa-eye text-2xl"></i></div>
        <div class="text-gray-500 text-xs font-bold uppercase tracking-wider">Content Views</div>
        <div class="text-2xl font-black text-gray-900">{{ $stats['views'] }}</div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="text-red-500 mb-2"><i class="fas fa-download text-2xl"></i></div>
        <div class="text-gray-500 text-xs font-bold uppercase tracking-wider">Downloads</div>
        <div class="text-2xl font-black text-gray-900">{{ $stats['downloads'] }}</div>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-50 flex justify-between items-center">
        <h3 class="font-bold text-gray-900">Recent Access Logs</h3>
        <span class="text-xs text-gray-400">Showing last 10 activities</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-[10px] uppercase font-bold">
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Product</th>
                    <th class="px-6 py-4">Action</th>
                    <th class="px-6 py-4">IP Address</th>
                    <th class="px-6 py-4">Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($recentLogs as $log)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $log->user->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $log->product->title }}</td>
                        <td class="px-6 py-4">
                            @if($log->action_type == 'view')
                                <span class="bg-blue-100 text-blue-700 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase">View</span>
                            @else
                                <span class="bg-green-100 text-green-700 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase">Download</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-400 font-mono text-xs">{{ $log->ip_address }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $log->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">No recent activity logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
