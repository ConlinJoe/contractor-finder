@props([
    'companyName' => '',
    'city' => '',
    'state' => ''
])

<div class="relative py-20 lg:py-32">
    <!-- Background Image with Overlay -->
    @php
        $heroImage = app()->environment('production')
            ? Vite::asset('resources/images/heroes/hero-home-01.jpg')
            : asset('images/heroes/hero-home-01.jpg');
    @endphp
    <!-- Background Image -->
    <img src="{{ $heroImage }}" alt="Hero Background" class="absolute inset-0 w-full h-full object-cover" style="z-index: 1;">
    <!-- <div class="absolute inset-0 bg-blue-900 bg-opacity-20" style="z-index: 2;"></div> -->

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="z-index: 3;">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Left Content -->
            <div class="text-white">
                <!-- AI-Powered Badge -->
                <div class="inline-flex items-center bg-blue-500 bg-opacity-20 backdrop-blur-sm rounded-full px-4 py-2 mb-6">
                    <i class="fas fa-robot text-blue-200 mr-2"></i>
                    <span class="text-blue-100 text-sm font-medium">AI-Powered Verification</span>
                </div>

                <!-- Main Headline -->
                <h1 class="text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    Find the Right <span class="text-blue-200">Contractor</span> Without the Headache
                </h1>

                <!-- Subheading -->
                <p class="text-xl text-blue-100 mb-8 leading-relaxed">
                    Stop wasting time on unreliable contractors. Our AI-powered platform instantly verifies credentials, reviews, and reputation.
                </p>

                <!-- Key Features -->
                <div class="flex flex-wrap gap-6 mb-8">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <span class="text-blue-100 font-medium">25,000+ Verified Contractors</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-400 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-sync-alt text-white text-sm"></i>
                        </div>
                        <span class="text-blue-100 font-medium">Real-time Updates</span>
                    </div>
                </div>
            </div>

            <!-- Right Content - Search Form -->
            <div class="lg:pl-8">
                <x-ui.card variant="elevated" padding="lg" class="bg-white bg-opacity-95 backdrop-blur-sm">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Start Your Search</h2>
                        <p class="text-gray-600">Get instant contractor verification</p>
                    </div>

                    <!-- Search Form -->
                    <form action="{{ route('search') }}" method="GET" class="space-y-4">
                        <!-- Tabs -->
                        <div class="flex border-b border-gray-200">
                            <button type="button" class="flex-1 py-2 px-4 text-center border-b-2 border-blue-600 text-blue-600 font-medium">
                                Search by Contractor
                            </button>
                            <button type="button" class="flex-1 py-2 px-4 text-center text-gray-500 font-medium">
                                Find a Contractor
                            </button>
                        </div>

                        <!-- Form Fields -->
                        <div>
                            <label for="companyName" class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                            <input
                                type="text"
                                id="companyName"
                                name="companyName"
                                value="{{ $companyName }}"
                                placeholder="e.g., Big Bully Turf"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required
                            >
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                            <input
                                type="text"
                                id="city"
                                name="city"
                                value="{{ $city }}"
                                placeholder="e.g., San Diego"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required
                            >
                        </div>

                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State (Optional)</label>
                            <input
                                type="text"
                                id="state"
                                name="state"
                                value="{{ $state }}"
                                placeholder="Enter state"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>

                        <!-- Search Button -->
                        <x-ui.button type="submit" variant="primary" size="lg" class="w-full">
                            Search Company
                            <i class="fas fa-arrow-right ml-2"></i>
                        </x-ui.button>

                        <!-- Learn More Link -->
                        <div class="text-center">
                            <a href="{{ route('how-it-works') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                Learn how our AI verification works
                            </a>
                        </div>
                    </form>
                </x-ui.card>
            </div>
        </div>
    </div>
</div>
