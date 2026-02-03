@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Product Strips</h1>
            <p class="text-gray-500">Manage sale and badge strips for all products.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-[10px] uppercase font-bold">
                        <th class="px-6 py-4">Product</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4 w-32">Sale Strip</th>
                        <th class="px-6 py-4 w-40">Badge Strip Text</th>
                        <th class="px-6 py-4 w-32">Badge Color</th>
                        <th class="px-6 py-4">Preview</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-amber-50 text-primary rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">{{ $product->title }}</div>
                                        <div class="text-xs text-gray-400">{{ $product->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg text-xs font-medium">{{ $product->category->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($product->sale_percentage)
                                    <span class="font-medium text-red-600">{{ $product->sale_percentage }}% OFF</span>
                                @else
                                    <span class="text-gray-400 text-xs italic">None</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($product->badge_strip_text)
                                    <span class="font-medium text-gray-700">{{ $product->badge_strip_text }}</span>
                                @else
                                    <span class="text-gray-400 italic text-xs">No Badge</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($product->badge_strip_color)
                                    <span
                                        class="text-xs text-gray-600">{{ \App\Models\Product::BADGE_STRIP_COLORS[$product->badge_strip_color] ?? 'N/A' }}</span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($product->badge_strip_text)
                                    @php
                                        $colorMap = [
                                            'red' => '#DC2626',
                                            'blue' => '#2563EB',
                                            'green' => '#16A34A',
                                            'golden' => '#D97706',
                                            'black' => '#1F2937',
                                            'pink' => '#EC4899',
                                            'orange' => '#EA580C',
                                        ];
                                        $bgColor = $colorMap[$product->badge_strip_color] ?? '#D97706';
                                    @endphp
                                    <div class="inline-block px-3 py-1 text-white text-xs font-bold rounded text-center"
                                        style="background-color: {{ $bgColor }};">
                                        {{ $product->badge_strip_text }}
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.badges.edit', $product->id) }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-amber-900 transition-colors text-xs font-bold">
                                    <i class="fas fa-edit"></i> Edit Strips
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="p-6 bg-gray-50/50 border-t border-gray-50">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection