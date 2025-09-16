@props([
    'icon' => 'fas fa-star',
    'title' => '',
    'description' => ''
])

<div class="text-center">
    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="{{ $icon }} text-blue-600 text-2xl"></i>
    </div>
    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $title }}</h3>
    <p class="text-gray-600 leading-relaxed">{{ $description }}</p>
</div>
