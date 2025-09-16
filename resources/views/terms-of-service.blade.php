@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-lg max-w-none">
            <h1 class="text-4xl font-bold text-gray-900 mb-8">Terms of Service</h1>

            <p class="text-lg text-gray-600 mb-8">
                <strong>Last updated:</strong> {{ date('F j, Y') }}
            </p>

            <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Acceptance of Terms</h2>
            <p class="text-gray-700 mb-6">
                By accessing and using WeSpeak Verify, you accept and agree to be bound by the terms and provision of this agreement.
            </p>

            <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Use License</h2>
            <p class="text-gray-700 mb-6">
                Permission is granted to temporarily download one copy of WeSpeak Verify for personal, non-commercial transitory viewing only.
            </p>

            <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Disclaimer</h2>
            <p class="text-gray-700 mb-6">
                The materials on WeSpeak Verify are provided on an 'as is' basis. WeSpeak Verify makes no warranties, expressed or implied.
            </p>

            <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Limitations</h2>
            <p class="text-gray-700 mb-6">
                In no event shall WeSpeak Verify or its suppliers be liable for any damages arising out of the use or inability to use the materials on WeSpeak Verify.
            </p>

            <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Contact Information</h2>
            <p class="text-gray-700 mb-6">
                If you have any questions about these Terms of Service, please contact us at legal@wespeakverify.com.
            </p>
        </div>
    </div>
</div>
@endsection
