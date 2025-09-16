@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-lg max-w-none">
            <h1 class="text-4xl font-bold text-gray-900 mb-8">Privacy Policy</h1>

            <p class="text-lg text-gray-600 mb-8">
                <strong>Last updated:</strong> {{ date('F j, Y') }}
            </p>

            <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Information We Collect</h2>
            <p class="text-gray-700 mb-6">
                We collect information you provide directly to us, such as when you create an account, use our services, or contact us for support.
            </p>

            <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">How We Use Your Information</h2>
            <p class="text-gray-700 mb-6">
                We use the information we collect to provide, maintain, and improve our services, process transactions, and communicate with you.
            </p>

            <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Information Sharing</h2>
            <p class="text-gray-700 mb-6">
                We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy.
            </p>

            <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Data Security</h2>
            <p class="text-gray-700 mb-6">
                We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.
            </p>

            <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Contact Us</h2>
            <p class="text-gray-700 mb-6">
                If you have any questions about this Privacy Policy, please contact us at privacy@wespeakverify.com.
            </p>
        </div>
    </div>
</div>
@endsection
