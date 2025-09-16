@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold text-white mb-6">How It Works</h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                Finding reliable contractors shouldn't be a gamble. Our AI-powered platform makes it simple and safe.
            </p>
        </div>
    </div>

    <!-- How It Works Steps -->
    <div class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <x-sections.feature-card
                    icon="fas fa-search"
                    title="1. Search"
                    description="Enter the contractor name and location. Our AI searches across multiple platforms to find the right match."
                />
                <x-sections.feature-card
                    icon="fas fa-star"
                    title="2. Verify"
                    description="We instantly verify credentials, check reviews, and validate license information from official sources."
                />
                <x-sections.feature-card
                    icon="fas fa-check-circle"
                    title="3. Decide"
                    description="Get a comprehensive report with ratings, reviews, and verification status to make an informed decision."
                />
            </div>
        </div>
    </div>

    <!-- Additional Info -->
    <div class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Why Choose WeSpeak Verify?</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-shield-alt text-green-600 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Verified Data</h3>
                        <p class="text-gray-600">All information is verified from official sources</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-bolt text-blue-600 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Instant Results</h3>
                        <p class="text-gray-600">Get comprehensive reports in seconds</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-brain text-purple-600 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">AI-Powered</h3>
                        <p class="text-gray-600">Advanced AI analyzes and summarizes reviews</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-yellow-600 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Trusted Network</h3>
                        <p class="text-gray-600">Access to 25,000+ verified contractors</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
