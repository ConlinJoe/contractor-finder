@props([
    'variant' => 'default',
    'size' => 'md'
])

@php
    $baseClasses = 'inline-flex items-center font-medium rounded-full';

    $variants = [
        'default' => 'bg-gray-100 text-gray-800',
        'primary' => 'bg-blue-100 text-blue-800',
        'success' => 'bg-green-100 text-green-800',
        'warning' => 'bg-yellow-100 text-yellow-800',
        'danger' => 'bg-red-100 text-red-800',
        'info' => 'bg-indigo-100 text-indigo-800'
    ];

    $sizes = [
        'sm' => 'px-2 py-1 text-xs',
        'md' => 'px-3 py-1 text-sm',
        'lg' => 'px-4 py-2 text-base'
    ];

    $classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
