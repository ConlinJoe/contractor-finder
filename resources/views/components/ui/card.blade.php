@props([
    'variant' => 'default',
    'padding' => 'md'
])

@php
    $baseClasses = 'bg-white rounded-lg shadow-md';

    $variants = [
        'default' => 'border border-gray-200',
        'elevated' => 'shadow-lg',
        'outlined' => 'border-2 border-gray-200',
        'gradient' => 'bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200'
    ];

    $paddings = [
        'sm' => 'p-4',
        'md' => 'p-6',
        'lg' => 'p-8',
        'xl' => 'p-10'
    ];

    $classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $paddings[$padding];
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
