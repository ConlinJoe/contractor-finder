@props([
    'name' => '',
    'role' => '',
    'rating' => 5,
    'quote' => ''
])

<div class="bg-white rounded-lg p-6 shadow-md">
    <div class="flex items-center mb-4">
        @for($i = 1; $i <= 5; $i++)
            <i class="fas fa-star {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
        @endfor
    </div>
    <blockquote class="text-gray-700 mb-4 italic">
        "{{ $quote }}"
    </blockquote>
    <div class="flex items-center">
        <div class="w-10 h-10 bg-gray-300 rounded-full mr-3 flex items-center justify-center">
            <i class="fas fa-user text-gray-500"></i>
        </div>
        <div>
            <div class="font-semibold text-gray-900">{{ $name }}</div>
            <div class="text-sm text-gray-600">{{ $role }}</div>
        </div>
    </div>
</div>
