@props([
    'label' => null,
    'name' => null,
    'value' => old($name),
    'options' => [],
    'required' => false,
    'disabled' => false,
    'placeholder' => 'Pilih...',
    'error' => $errors->has($name) ? $errors->first($name) : null,
])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <select 
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => "mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"]) }}
    >
        @if($placeholder)
            <option value="" {{ $value == '' ? 'selected' : '' }}>{{ $placeholder }}</option>
        @endif
        
        @foreach($options as $key => $option)
            <option value="{{ $key }}" {{ $value == $key ? 'selected' : '' }}>
                {{ $option }}
            </option>
        @endforeach
    </select>
    
    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>