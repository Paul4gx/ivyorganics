@props(['active' => false, 'href' => '#'])

@php
    $classes = $active
        ? 'text-green-700 block px-3 py-2 rounded-md text-base font-medium'
        : 'text-gray-600 hover:text-green-700 block px-3 py-2 rounded-md text-base font-medium';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>