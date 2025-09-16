@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-5xl font-bold text-gray-900 mb-6">Pricing</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Start with our free tier to experience the power of AI-driven contractor vetting, then upgrade for unlimited access.
            </p>
        </div>

        <!-- Pricing Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <!-- Free Tier -->
            <x-sections.pricing-card
                title="Free Tier"
                price="0"
                period=""
                description="Perfect for trying our service"
                :features="[
                    ['text' => 'AI-powered contractor search', 'included' => true],
                    ['text' => 'Basic contractor profiles', 'included' => true],
                    ['text' => '1-2 searches per month', 'included' => true],
                    ['text' => 'Basic review summaries', 'included' => true],
                    ['text' => 'Detailed vetting reports', 'included' => false],
                    ['text' => 'Direct contact information', 'included' => false],
                    ['text' => 'Priority support', 'included' => false],
                    ['text' => 'Export reports', 'included' => false]
                ]"
                buttonText="Start Free"
                buttonVariant="outline"
                buttonHref="{{ route('search') }}"
            />

            <!-- Membership -->
            <x-sections.pricing-card
                title="Membership"
                price="29"
                period="/month"
                description="Unlimited contractor vetting"
                :features="[
                    ['text' => 'AI-powered contractor search', 'included' => true],
                    ['text' => 'Basic contractor profiles', 'included' => true],
                    ['text' => 'Unlimited searches', 'included' => true],
                    ['text' => 'Advanced review summaries', 'included' => true],
                    ['text' => 'Detailed vetting reports', 'included' => true],
                    ['text' => 'Direct contact information', 'included' => true],
                    ['text' => 'Priority support', 'included' => true],
                    ['text' => 'Export reports', 'included' => true]
                ]"
                buttonText="Get Membership"
                buttonVariant="primary"
                buttonHref="{{ route('search') }}"
                :popular="true"
            />
        </div>

        <!-- FAQ Section -->
        <div class="mt-20">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Frequently Asked Questions</h2>
            <div class="max-w-3xl mx-auto space-y-8">
                <div class="bg-white rounded-lg p-6 shadow-md">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">What's included in the free tier?</h3>
                    <p class="text-gray-600">The free tier includes basic contractor search, limited searches per month, and basic review summaries. Perfect for trying out our service.</p>
                </div>
                <div class="bg-white rounded-lg p-6 shadow-md">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I upgrade or downgrade anytime?</h3>
                    <p class="text-gray-600">Yes, you can upgrade or downgrade your plan at any time. Changes take effect immediately.</p>
                </div>
                <div class="bg-white rounded-lg p-6 shadow-md">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Is there a contract or commitment?</h3>
                    <p class="text-gray-600">No, there's no contract or long-term commitment. You can cancel anytime with no penalties.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
