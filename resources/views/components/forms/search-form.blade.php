@props([
    'companyName' => '',
    'city' => '',
    'state' => '',
    'searchMode' => 'company',
    'selectedJobTypeId' => null,
    'jobTypeCity' => '',
    'jobTypeState' => '',
    'context' => 'search', // 'homepage' or 'search'
    'jobTypes' => []
])

@php
    // Load job types from database if not provided
    if (empty($jobTypes)) {
        $jobTypes = \App\Models\JobType::active()->ordered()->get();
    }
@endphp

<div class="search-form-container" x-data="{ activeTab: '{{ $searchMode === 'job_type' ? 'jobtype' : 'contractor' }}' }">
    <x-ui.card variant="elevated" padding="lg" class="bg-white bg-opacity-95 backdrop-blur-sm">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Start Your Search</h2>
            <p class="text-gray-600">Get instant contractor verification</p>
        </div>

        <!-- Tabs -->
        <div class="flex border-b border-gray-200 mb-6">
            <button type="button"
                    @click="activeTab = 'contractor'"
                    :class="activeTab === 'contractor' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'"
                    class="flex-1 py-2 px-4 text-center border-b-2 font-medium transition-colors">
                Search by Contractor
            </button>
            <button type="button"
                    @click="activeTab = 'jobtype'"
                    :class="activeTab === 'jobtype' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'"
                    class="flex-1 py-2 px-4 text-center border-b-2 font-medium transition-colors">
                Find a Contractor
            </button>
        </div>

        <!-- Search by Contractor Form -->
        @if($context === 'homepage')
            <form action="{{ route('search') }}" method="GET" class="space-y-4" x-show="activeTab === 'contractor'">
        @else
            <div class="space-y-4" x-show="activeTab === 'contractor'">
                <form wire:submit.prevent="search" class="space-y-4">
        @endif
            <!-- Form Fields -->
            <div class="form-grid">
                <div>
                    <label for="companyName" class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                    <input
                        type="text"
                        id="companyName"
                        name="companyName"
                        value="{{ $companyName }}"
                        @if($context === 'search') wire:model="companyName" @endif
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
                        @if($context === 'search') wire:model="city" @endif
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
                        @if($context === 'search') wire:model="state" @endif
                        placeholder="Enter state"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
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
        @if($context === 'homepage')
            </form>
        @else
                </form>
            </div>
        @endif

        <!-- Find a Contractor Form -->
        @if($context === 'homepage')
            <form action="{{ route('search') }}" method="GET" class="space-y-4" x-show="activeTab === 'jobtype'">
                <input type="hidden" name="searchMode" value="job_type">
        @else
            <div class="space-y-4" x-show="activeTab === 'jobtype'">
                <form wire:submit.prevent="search" class="space-y-4">
        @endif
            <!-- Job Type Dropdown -->
            <div class="form-grid">
                <div class="col-span-full">
                    <label for="jobType" class="block text-sm font-medium text-gray-700 mb-2">What type of work do you need?</label>
                    <select
                        id="jobType"
                        name="selectedJobTypeId"
                        @if($context === 'search') wire:model="selectedJobTypeId" @endif
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >
                        <option value="">Select job type...</option>
                        @foreach($jobTypes as $jobType)
                            <option value="{{ $jobType->id }}" {{ $selectedJobTypeId == $jobType->id ? 'selected' : '' }}>
                                {{ $jobType->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="jobTypeCity" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                    <input
                        type="text"
                        id="jobTypeCity"
                        name="jobTypeCity"
                        value="{{ $jobTypeCity ?: $city }}"
                        @if($context === 'search') wire:model="jobTypeCity" @endif
                        placeholder="e.g., San Diego"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >
                </div>

                <div>
                    <label for="jobTypeState" class="block text-sm font-medium text-gray-700 mb-2">State (Optional)</label>
                    <input
                        type="text"
                        id="jobTypeState"
                        name="jobTypeState"
                        value="{{ $jobTypeState ?: $state }}"
                        @if($context === 'search') wire:model="jobTypeState" @endif
                        placeholder="Enter state"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
            </div>

            <!-- Search Button -->
            <x-ui.button type="submit" variant="primary" size="lg" class="w-full">
                Find Contractors
                <i class="fas fa-search ml-2"></i>
            </x-ui.button>

            <!-- Learn More Link -->
            <div class="text-center">
                <a href="{{ route('how-it-works') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    Learn how our AI verification works
                </a>
            </div>
        @if($context === 'homepage')
            </form>
        @else
                </form>
            </div>
        @endif
    </x-ui.card>
</div>
