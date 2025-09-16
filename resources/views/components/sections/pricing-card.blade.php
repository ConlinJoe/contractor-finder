@props([
    'title' => '',
    'price' => '',
    'period' => '/month',
    'description' => '',
    'features' => [],
    'buttonText' => 'Get Started',
    'buttonVariant' => 'primary',
    'popular' => false,
    'buttonHref' => '#'
])

<div class="relative {{ $popular ? 'ring-2 ring-blue-600' : '' }} rounded-2xl bg-white p-8 shadow-lg">
    @if($popular)
        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
            <x-ui.badge variant="primary" size="lg">Most Popular</x-ui.badge>
        </div>
    @endif

    <div class="text-center mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $title }}</h3>
        <div class="flex items-baseline justify-center">
            <span class="text-5xl font-bold text-gray-900">${{ $price }}</span>
            <span class="text-gray-600 ml-1">{{ $period }}</span>
        </div>
        <p class="text-gray-600 mt-2">{{ $description }}</p>
    </div>

    <ul class="space-y-4 mb-8">
        @foreach($features as $feature)
            <li class="flex items-start">
                @if($feature['included'])
                    <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                @else
                    <i class="fas fa-times text-red-400 mt-1 mr-3"></i>
                @endif
                <span class="text-gray-700 {{ !$feature['included'] ? 'line-through opacity-60' : '' }}">
                    {{ $feature['text'] }}
                </span>
            </li>
        @endforeach
    </ul>

    <x-ui.button
        variant="{{ $buttonVariant }}"
        size="lg"
        href="{{ $buttonHref }}"
        class="w-full justify-center"
    >
        {{ $buttonText }}
    </x-ui.button>
</div>
