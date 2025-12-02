@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'disabled' => false,
])

@php
$variantClasses = match($variant) {
    'primary' => 'bg-blue-600 hover:bg-blue-700 text-white',
    'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white',
    'success' => 'bg-green-600 hover:bg-green-700 text-white',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white',
    'warning' => 'bg-yellow-600 hover:bg-yellow-700 text-white',
    'info' => 'bg-indigo-600 hover:bg-indigo-700 text-white',
    default => 'bg-blue-600 hover:bg-blue-700 text-white',
};

$sizeClasses = match($size) {
    'sm' => 'px-3 py-1.5 text-xs',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
    default => 'px-4 py-2 text-sm',
};

$disabledClass = $disabled ? 'opacity-50 cursor-not-allowed' : '';
@endphp

<button 
    type="{{ $type }}" 
    {{ $attributes->merge(['class' => "inline-flex items-center rounded-md font-semibold transition ease-in-out duration-150 {$variantClasses} {$sizeClasses} {$disabledClass}"]) }}
    @disabled($disabled)
>
    {{ $slot }}
</button>