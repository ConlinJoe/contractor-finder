@props([
    'icon' => 'fas fa-star',
    'number' => '',
    'label' => ''
])

<div class="text-center">
    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
        <i class="{{ $icon }} text-blue-600 text-lg"></i>
    </div>
    <div class="text-3xl font-bold text-gray-900 mb-1">{{ $number }}</div>
    <div class="text-gray-600">{{ $label }}</div>
</div>
