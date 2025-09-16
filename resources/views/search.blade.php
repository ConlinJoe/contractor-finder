@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Search Contractors</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Find and verify contractors with our AI-powered platform. Get instant results with reviews, ratings, and license verification.
            </p>
        </div>

        <!-- Search Component -->
        <div class="max-w-4xl mx-auto">
            @livewire('company-screener', [
                'companyName' => $companyName ?? '',
                'city' => $city ?? '',
                'state' => $state ?? ''
            ])
        </div>
    </div>
</div>
@endsection
