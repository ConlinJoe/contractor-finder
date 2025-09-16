@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-bold text-gray-900 mb-6">Frequently Asked Questions</h1>
            <p class="text-xl text-gray-600">
                Find answers to common questions about our contractor verification platform.
            </p>
        </div>

        <div class="space-y-8">
            <div class="bg-white rounded-lg p-6 shadow-md">
                <h3 class="text-xl font-semibold text-gray-900 mb-3">How does WeSpeak Verify work?</h3>
                <p class="text-gray-700">We use AI to search across multiple platforms, verify contractor credentials, check reviews, and provide you with a comprehensive report in seconds.</p>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-md">
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Is the information accurate and up-to-date?</h3>
                <p class="text-gray-700">Yes, we pull data from official sources and update our information regularly to ensure accuracy and reliability.</p>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-md">
                <h3 class="text-xl font-semibold text-gray-900 mb-3">How much does it cost?</h3>
                <p class="text-gray-700">We offer a free tier with limited searches and a membership plan for unlimited access. See our <a href="{{ route('pricing') }}" class="text-blue-600 hover:underline">pricing page</a> for details.</p>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-md">
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Can I trust the reviews and ratings?</h3>
                <p class="text-gray-700">We aggregate reviews from multiple trusted sources and use AI to analyze and summarize them, giving you a balanced view of each contractor.</p>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-md">
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Do you cover all contractors?</h3>
                <p class="text-gray-700">We have access to over 25,000 verified contractors across the United States, with more being added regularly.</p>
            </div>
        </div>
    </div>
</div>
@endsection
