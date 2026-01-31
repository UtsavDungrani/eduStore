@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Product Badges</h1>
        <p class="text-gray-500">Manage badge settings for all products.</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-[10px] uppercase font-bold">
                    <th class="px-6 py-4">Product</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4">Badge Text</th>
                    <th class="px-6 py-4">Shape</th>
                    <th class="px-6 py-4">Color</th>
                    <th class="px-6 py-4">Preview</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-amber-50 text-primary rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900">{{ $product->title }}</div>
                                    <div class="text-xs text-gray-400">{{ $product->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg text-xs font-medium">{{ $product->category->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($product->highlight_badge)
                                <span class="font-medium text-gray-700">{{ $product->highlight_badge }}</span>
                            @else
                                <span class="text-gray-400 italic">No Badge</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($product->highlight_badge_shape)
                                <span class="text-xs text-gray-600">{{ \App\Models\Product::HIGHLIGHT_BADGE_SHAPES[$product->highlight_badge_shape] ?? 'N/A' }}</span>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($product->highlight_badge_color)
                                <span class="text-xs text-gray-600">{{ \App\Models\Product::HIGHLIGHT_BADGE_COLORS[$product->highlight_badge_color] ?? 'N/A' }}</span>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($product->highlight_badge)
                                @php
                                    $shape = $product->highlight_badge_shape ?? 'pill';
                                    $badgeColor = $product->highlight_badge_color ?? 'golden';

                                    // Color Styles
                                    $colorStyles = [
                                        'golden' => 'background: linear-gradient(135deg, #FDE68A 0%, #F59E0B 50%, #B45309 100%); color: black; border-top: 1px solid rgba(255,255,255,0.4); border-left: 1px solid rgba(255,255,255,0.2);',
                                        'red' => 'background: linear-gradient(135deg, #f87171 0%, #dc2626 50%, #991b1b 100%); color: white; border-top: 1px solid rgba(255,255,255,0.3); border-left: 1px solid rgba(255,255,255,0.1);',
                                        'blue' => 'background: linear-gradient(135deg, #60a5fa 0%, #2563eb 50%, #1e40af 100%); color: white; border-top: 1px solid rgba(255,255,255,0.3); border-left: 1px solid rgba(255,255,255,0.1);',
                                        'green' => 'background: linear-gradient(135deg, #34d399 0%, #059669 50%, #065f46 100%); color: white; border-top: 1px solid rgba(255,255,255,0.3); border-left: 1px solid rgba(255,255,255,0.1);',
                                        'black' => 'background: linear-gradient(135deg, #4b5563 0%, #111827 50%, #000000 100%); color: white; border-top: 1px solid rgba(255,255,255,0.2); border-left: 1px solid rgba(255,255,255,0.1);',
                                        'pink' => 'background: linear-gradient(135deg, #f472b6 0%, #db2777 50%, #9f1239 100%); color: white; border-top: 1px solid rgba(255,255,255,0.3); border-left: 1px solid rgba(255,255,255,0.1);',
                                        'orange' => 'background: linear-gradient(135deg, #fb923c 0%, #ea580c 50%, #9a3412 100%); color: white; border-top: 1px solid rgba(255,255,255,0.3); border-left: 1px solid rgba(255,255,255,0.1);',
                                    ];

                                    // Shape Styles (Scaled for Admin Table)
                                    $shapeClasses = 'rounded-full px-2 py-0.5'; // Default pill
                                    $clipPath = '';
                                    
                                    switch($shape) {
                                        case 'soft_rectangle': $shapeClasses = 'rounded-md px-2 py-0.5'; break;
                                        case 'tag': $shapeClasses = 'rounded-r-full rounded-l-none pl-3 pr-2 py-0.5'; break;
                                        case 'circle': $shapeClasses = 'rounded-full h-8 w-8 flex items-center justify-center p-0.5 text-center leading-tight text-[8px]'; break;
                                        case 'square': $shapeClasses = 'rounded-none h-8 w-8 flex items-center justify-center p-0.5 text-center leading-tight text-[8px]'; break;
                                        case 'banner': 
                                            $shapeClasses = 'w-8 pt-2 pb-3 px-0.5 min-h-[3.5rem] flex items-center justify-center text-center leading-none text-[8px]'; 
                                            $clipPath = 'polygon(100% 0, 100% 100%, 50% 85%, 0 100%, 0 0)'; 
                                            break;
                                        case 'flag': 
                                            $shapeClasses = 'w-20 pl-2 pr-3 py-1'; 
                                            $clipPath = 'polygon(0 0, 100% 0, 95% 50%, 100% 100%, 0 100%)'; 
                                            break;
                                        case 'arrow': 
                                            $shapeClasses = 'pl-3 pr-4 py-1'; 
                                            $clipPath = 'polygon(0 0, 95% 0, 100% 50%, 95% 100%, 0 100%)'; 
                                            break;
                                    }
                                @endphp
                                <div class="flex items-center">
                                    <div class="text-[10px] font-bold {{ $shapeClasses }} shadow-sm uppercase tracking-wider flex items-center justify-center gap-1 transition-all"
                                         style="{{ ($colorStyles[$badgeColor] ?? $colorStyles['golden']) . ($clipPath ? ' clip-path: ' . $clipPath . ';' : '') }}">
                                        <span class="{{ in_array($shape, ['circle', 'square', 'banner']) ? 'whitespace-normal leading-none break-words' : 'whitespace-nowrap' }}">
                                            {!! $shape === 'banner' ? str_replace(' ', '<br>', $product->highlight_badge) : $product->highlight_badge !!}
                                        </span>
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.badges.edit', $product->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-amber-900 transition-colors text-xs font-bold">
                                <i class="fas fa-edit"></i> Edit Badge
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
