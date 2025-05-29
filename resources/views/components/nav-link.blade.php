@props(['active' => false, 'href' => '#'])

@php
    $classes = $active
        ? 'text-green-700 border-b-2 border-green-700 px-3 py-2 text-sm font-medium'
        : 'text-gray-600 hover:text-green-700 px-3 py-2 text-sm font-medium';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>