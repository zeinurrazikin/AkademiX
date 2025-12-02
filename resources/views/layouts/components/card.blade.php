<div class="bg-white rounded-lg shadow p-6 {{ $class ?? '' }}">
    @if(isset($header))
        <div class="mb-4">
            {{ $header }}
        </div>
    @endif
    
    <div class="space-y-4">
        {{ $slot }}
    </div>
    
    @if(isset($footer))
        <div class="mt-6 pt-4 border-t border-gray-200">
            {{ $footer }}
        </div>
    @endif
</div>