@props([
    'label' => null,
    'name' => '',
    'type' => 'text',
    'value' => null,
    'required' => false,
    'variant' => 'boxed',
])

@php
    $inputClass = $variant === 'underline'
        ? 'w-full bg-transparent border-0 border-b-2 border-gray-200 px-0 py-2 text-[#111218] focus:outline-none focus:ring-0 focus:border-[var(--brand-primary,#2B3288)] transition-colors placeholder:text-gray-400 text-sm'
        : 'w-full rounded-xl border border-[var(--brand-border,#C9CEDF)] bg-white px-4 py-2.5 text-sm text-[#111218] focus:outline-none focus:border-[var(--brand-primary,#2B3288)] focus:ring-2 focus:ring-[var(--brand-primary,#2B3288)]/15 transition-colors placeholder:text-gray-400';
@endphp

<div class="w-full">
    @if($label)
        <label for="{{ $attributes->get('id', $name) }}" class="block text-xs font-bold uppercase tracking-wider text-[#171512] mb-1.5">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $attributes->get('id', $name) }}"
        value="{{ old($name, $value) }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => $inputClass]) }}
    >

    @if($name)
        @error($name)
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    @endif
</div>
