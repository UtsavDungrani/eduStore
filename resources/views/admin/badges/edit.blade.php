@extends('layouts.admin')

@section('content')
    <x-badge-styles />

    <div class="mb-8">
        <a href="{{ route('admin.badges.index') }}" class="text-sm text-gray-500 hover:text-primary mb-2 inline-block"><i
                class="fas fa-arrow-left"></i> Back to Badges</a>
        <h1 class="text-3xl font-bold text-gray-900">Edit Badge: {{ $product->title }}</h1>
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

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 space-y-6">
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

                <!-- Badge Settings -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-star text-amber-500"></i> Badge Text
                        </label>
                        <select name="highlight_badge" id="badge_text"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary">
                            <option value="">None (No Badge)</option>
                            @foreach(\App\Models\Product::HIGHLIGHT_BADGE_OPTIONS as $option)
                                <option value="{{ $option }}" {{ old('highlight_badge', $product->highlight_badge) == $option ? 'selected' : '' }}>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-shapes text-primary"></i> Badge Shape
                        </label>
                        <select name="highlight_badge_shape" id="badge_shape"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary">
                            @foreach(\App\Models\Product::HIGHLIGHT_BADGE_SHAPES as $value => $label)
                                <option value="{{ $value }}" {{ old('highlight_badge_shape', $product->highlight_badge_shape ?? 'pill') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-palette text-primary"></i> Badge Color
                        </label>
                        <select name="highlight_badge_color" id="badge_color"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary">
                            @foreach(\App\Models\Product::HIGHLIGHT_BADGE_COLORS as $value => $label)
                                <option value="{{ $value }}" {{ old('highlight_badge_color', $product->highlight_badge_color ?? 'golden') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Live Preview -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-8 rounded-xl border border-gray-200">
                    <h3 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fas fa-eye text-primary"></i> Badge Preview
                    </h3>
                    <div class="flex items-center justify-center min-h-[120px]">
                        <span id="badge_preview" class="badge badge-pill badge-golden text-sm font-bold">
                            {{ $product->highlight_badge ?: 'No Badge' }}
                        </span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4">
                    <button type="submit"
                        class="flex-1 bg-primary text-white py-4 rounded-2xl font-bold hover:bg-amber-900 transition-all shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Update Badge
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
        // Live preview update
        const badgeText = document.getElementById('badge_text');
        const badgeShape = document.getElementById('badge_shape');
        const badgeColor = document.getElementById('badge_color');
        const badgePreview = document.getElementById('badge_preview');

        function updatePreview() {
            const text = badgeText.value || 'No Badge';
            const shape = badgeShape.value || 'pill';
            const color = badgeColor.value || 'golden';

            // Update text content
            badgePreview.textContent = text;

            // Reset all classes
            badgePreview.className = 'badge';

            // Add shape and color classes
            badgePreview.classList.add('badge-' + shape);
            badgePreview.classList.add('badge-' + color);
            badgePreview.classList.add('text-sm', 'font-bold');
        }

        badgeText.addEventListener('input', updatePreview);
        badgeShape.addEventListener('input', updatePreview);
        badgeColor.addEventListener('input', updatePreview);

        // Also listen to 'change' event for better compatibility
        badgeText.addEventListener('change', updatePreview);
        badgeShape.addEventListener('change', updatePreview);
        badgeColor.addEventListener('change', updatePreview);

        // Initialize preview
        updatePreview();
    </script>
@endsection