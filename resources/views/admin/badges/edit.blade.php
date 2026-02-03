@extends('layouts.admin')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.badges.index') }}" class="text-sm text-gray-500 hover:text-primary mb-2 inline-block"><i
                class="fas fa-arrow-left"></i> Back to Badges</a>
        <h1 class="text-3xl font-bold text-gray-900">Edit Product Strips: {{ $product->title }}</h1>
    </div>

    <div class="max-w-4xl">
        <form action="{{ route('admin.badges.update', $product) }}" method="POST">
            @csrf
            @method('PATCH')

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-red-800">There were {{ $errors->count() }} errors with your
                                submission</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 space-y-8">
                <!-- Product Info (Read-only) -->
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-amber-50 text-primary rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-file-pdf text-xl"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900">{{ $product->title }}</div>
                            <div class="text-sm text-gray-500">{{ $product->category->name }}</div>
                        </div>
                    </div>
                </div>

                <!-- Strip 2: Badge Strip (Customizable) -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-tag text-primary"></i> Badge Strip
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Badge Text Selection -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                <i class="fas fa-star text-amber-500"></i> Badge Text
                            </label>
                            <select name="badge_strip_text" id="badge_text"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary">
                                <option value="">None (No Badge Strip)</option>
                                @foreach(\App\Models\Product::BADGE_STRIP_OPTIONS as $option)
                                    <option value="{{ $option }}" {{ old('badge_strip_text', $product->badge_strip_text ?? '') == $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Badge Color Selection -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                <i class="fas fa-palette text-primary"></i> Badge Color
                            </label>
                            <select name="badge_strip_color" id="badge_color"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary">
                                @foreach(\App\Models\Product::BADGE_STRIP_COLORS as $value => $label)
                                    <option value="{{ $value }}" {{ old('badge_strip_color', $product->badge_strip_color ?? 'golden') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Live Preview -->
                    <div class="mt-6 bg-gradient-to-br from-gray-50 to-gray-100 p-8 rounded-xl border border-gray-200">
                        <h4 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-eye text-primary"></i> Preview
                        </h4>
                        <div class="flex items-center justify-center min-h-[80px]">
                            <div id="preview_container" class="flex flex-col gap-4 items-center w-full max-w-xs">
                                <!-- Sale Strip Preview -->
                                <div
                                    class="w-full relative h-24 bg-white rounded-lg border border-gray-200 overflow-hidden flex items-center justify-center">
                                    <div class="absolute top-0 right-0 w-20 h-20 overflow-hidden z-10">
                                        <div
                                            class="absolute top-4 -right-8 w-28 bg-red-600 text-white text-xs font-black py-1 text-center transform rotate-45 shadow-md uppercase">
                                            OFF
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-400">Sale Strip (Auto)</p>
                                </div>

                                <!-- Badge Strip Preview -->
                                <div
                                    class="w-full relative h-24 bg-white rounded-lg border border-gray-200 overflow-hidden flex items-center justify-center">
                                    <div id="badge_preview"
                                        class="badge-strip-preview text-white font-bold text-sm uppercase tracking-wide shadow-lg"
                                        style="padding: 8px 20px; border-radius: 4px; background-color: #D97706;">
                                        <span id="badge_preview_text">Most Bought</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 border-t border-gray-100">
                    <button type="submit"
                        class="flex-1 bg-primary text-white py-4 rounded-2xl font-bold hover:bg-amber-900 transition-all shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Save Strips
                    </button>
                    <a href="{{ route('admin.badges.index') }}"
                        class="flex-1 text-center py-4 rounded-2xl font-bold bg-gray-50 text-gray-500 hover:bg-gray-100 transition-all border border-gray-100">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Color map for preview
        const colorMap = {
            'red': '#DC2626',
            'blue': '#2563EB',
            'green': '#16A34A',
            'golden': '#D97706',
            'black': '#1F2937',
            'pink': '#EC4899',
            'orange': '#EA580C',
        };

        // Live preview update
        const badgeText = document.getElementById('badge_text');
        const badgeColor = document.getElementById('badge_color');
        const badgePreview = document.getElementById('badge_preview');
        const badgePreviewText = document.getElementById('badge_preview_text');

        function updatePreview() {
            const text = badgeText.value || 'No Badge';
            const color = badgeColor.value || 'golden';

            // Update text
            badgePreviewText.textContent = text;

            // Update color
            badgePreview.style.backgroundColor = colorMap[color] || colorMap['golden'];

            // Hide preview if no text selected
            if (!badgeText.value) {
                badgePreview.style.opacity = '0.3';
            } else {
                badgePreview.style.opacity = '1';
            }
        }

        badgeText.addEventListener('change', updatePreview);
        badgeColor.addEventListener('change', updatePreview);

        // Initialize preview
        updatePreview();
    </script>
@endsection