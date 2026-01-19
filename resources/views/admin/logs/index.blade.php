@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Access Logs</h1>
    <p class="text-gray-500">Track all content view and download activities.</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left font-medium">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-[10px] uppercase font-bold">
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Product</th>
                    <th class="px-6 py-4">Action</th>
                    <th class="px-6 py-4">IP Address</th>
                    <th class="px-6 py-4">User Agent</th>
                    <th class="px-6 py-4">Timestamp</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-gray-900">{{ $log->user->name }}</div>
                            <div class="text-[10px] text-gray-400 font-mono">{{ $log->user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-600 truncate max-w-[200px] block" title="{{ $log->product->title }}">
                                {{ $log->product->title }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($log->action_type == 'view')
                                <span class="bg-blue-100 text-blue-700 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase">View</span>
                            @else
                                <span class="bg-green-100 text-green-700 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase">Download</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs font-mono text-gray-500">{{ $log->ip_address }}</td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] text-gray-400 truncate max-w-[150px] block" title="{{ $log->user_agent }}">
                                {{ $log->user_agent }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ $log->created_at->format('M d, Y H:i') }}
                            <div class="text-[10px] text-gray-400">{{ $log->created_at->diffForHumans() }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">No access logs found yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
        <div class="p-6 bg-gray-50/50 border-t border-gray-50">
            {{ $logs->links() }}
        </div>
    @endif
</div>
@endsection
