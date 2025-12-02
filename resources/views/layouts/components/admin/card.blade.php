<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500">{{ $title }}</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $value }}</p>
        </div>
        <div class="p-3 rounded-full bg-{{ $color ?? 'blue' }}-100 text-{{ $color ?? 'blue' }}-600">
            {{ $icon }}
        </div>
    </div>
    @if(isset($description))
        <p class="mt-2 text-sm text-gray-500">{{ $description }}</p>
    @endif
</div>