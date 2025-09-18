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
                <x-forms.search-form
                    :companyName="$companyName"
                    :city="$city"
                    :state="$state"
                    context="homepage"
                />
            </div>
        </div>
    </div>
</div>
