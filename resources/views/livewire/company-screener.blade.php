<div class="py-8" x-data="{ activeTab: '{{ $searchMode }}' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($showForm)
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Contractor Search Tool</h1>
                <p class="text-lg text-gray-600">Search for contractors by company name or job type to get comprehensive reviews and scoring</p>
            </div>

            <!-- Tab Navigation -->
        <div class="mb-8">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 justify-center">
                    <button
                        @click="activeTab = 'company'; $wire.switchToCompanySearch()"
                        :disabled="$wire.isLoading"
                        :class="activeTab === 'company' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Search by Contractor Name
                    </button>
                    <button
                        @click="activeTab = 'job_type'; $wire.switchToJobTypeSearch()"
                        :disabled="$wire.isLoading"
                        :class="activeTab === 'job_type' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Search by Job Type
                    </button>
                </nav>
            </div>
        </div>

        <!-- Company Search Form -->
        <div x-show="activeTab === 'company'" class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form wire:submit.prevent="search" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="companyName" class="block text-sm font-medium text-gray-700 mb-1">
                            Company Name *
                        </label>
                        <input
                            type="text"
                            id="companyName"
                            wire:model="companyName"
                            @if($isLoading) disabled @endif
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent disabled:opacity-50 disabled:cursor-not-allowed"
                            placeholder="Enter company name"
                        >
                        @error('companyName')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                            City *
                        </label>
                        <input
                            type="text"
                            id="city"
                            wire:model="city"
                            @if($isLoading) disabled @endif
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent disabled:opacity-50 disabled:cursor-not-allowed"
                            placeholder="Enter city"
                        >
                        @error('city')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-1">
                            State (Optional)
                        </label>
                        <input
                            type="text"
                            id="state"
                            wire:model="state"
                            @if($isLoading) disabled @endif
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent disabled:opacity-50 disabled:cursor-not-allowed"
                            placeholder="Enter state"
                        >
                    </div>
                </div>

                <div class="flex justify-center">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="search"
                        class="bg-green-700 text-white uppercase text-sm font-bold px-6 py-2 rounded-md hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center justify-center min-w-[140px]"
                    >
                        <span wire:loading.class="hidden" wire:target="search">Search Company</span>
                        <span wire:loading.class="flex" wire:loading.class.remove="hidden" wire:target="search" class="hidden items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Searching...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Job Type Search Form -->
        <div x-show="activeTab === 'job_type'" class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form wire:submit.prevent="search" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="selectedJobTypeId" class="block text-sm font-medium text-gray-700 mb-1">
                            Job Type *
                        </label>
                        <select
                            id="selectedJobTypeId"
                            wire:model="selectedJobTypeId"
                            @if($isLoading) disabled @endif
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <option value="">Select a job type...</option>
                            @foreach($jobTypes as $jobType)
                                <option value="{{ $jobType['id'] }}">{{ $jobType['name'] }}</option>
                            @endforeach
                        </select>
                        @error('selectedJobTypeId')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jobTypeCity" class="block text-sm font-medium text-gray-700 mb-1">
                            City *
                        </label>
                        <input
                            type="text"
                            id="jobTypeCity"
                            wire:model="jobTypeCity"
                            @if($isLoading) disabled @endif
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent disabled:opacity-50 disabled:cursor-not-allowed"
                            placeholder="Enter city"
                        >
                        @error('jobTypeCity')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jobTypeState" class="block text-sm font-medium text-gray-700 mb-1">
                            State (Optional)
                        </label>
                        <input
                            type="text"
                            id="jobTypeState"
                            wire:model="jobTypeState"
                            @if($isLoading) disabled @endif
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent disabled:opacity-50 disabled:cursor-not-allowed"
                            placeholder="Enter state"
                        >
                    </div>

                    <div>
                        <label for="jobTypeRadius" class="block text-sm font-medium text-gray-700 mb-1">
                            Search Radius
                        </label>
                        <select
                            id="jobTypeRadius"
                            wire:model="jobTypeRadius"
                            @if($isLoading) disabled @endif
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <option value="10">10 miles</option>
                            <option value="20">20 miles</option>
                            <option value="30">30 miles</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-center">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="search"
                        class="bg-green-700 text-white uppercase text-sm font-bold px-6 py-2 rounded-md hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center justify-center min-w-[160px]"
                    >
                        <span wire:loading.class="hidden" wire:target="search">Find Contractors</span>
                        <span wire:loading.class="flex" wire:loading.class.remove="hidden" wire:target="search" class="hidden items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Finding Contractors...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Error Message -->
        @if($errorMessage)
            <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800">{{ $errorMessage }}</p>
                    </div>
                </div>
            </div>
        @endif
        @endif

        <!-- API Issues Warning -->
        @if(!empty($apiIssues))
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Service Status Notice</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($apiIssues as $issue)
                                    <li>{{ $issue }}</li>
                                @endforeach
                            </ul>
                            <p class="mt-2">
                                <strong>Note:</strong> While we use multiple sources for information, some features may not work properly. For the most accurate results, please try again in a few minutes.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Loading State -->
        @if($isLoading)
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-700 mx-auto mb-4"></div>
                @if($searchMode === 'company')
                    <p class="text-gray-600 font-medium">Analyzing company data...</p>
                    <p class="text-sm text-gray-500 mt-2">Searching reviews, checking license status, and generating AI analysis</p>
                @else
                    <p class="text-gray-600 font-medium">Finding contractors...</p>
                    <p class="text-sm text-gray-500 mt-2">Searching multiple sources and analyzing contractor data</p>
                @endif
                <div class="mt-4">
                    <div class="bg-gray-200 rounded-full h-2 w-full max-w-xs mx-auto">
                        <div class="bg-green-600 h-2 rounded-full animate-pulse" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Business Selection -->
        @if($showBusinessSelection)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Multiple businesses found. Please select one:</h2>
                <p class="text-sm text-gray-600 mb-4">Note: Review counts shown are from Yelp only. The final count will include reviews from multiple sources.</p>
                <div class="space-y-4">
                    @foreach($businesses as $business)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $business['name'] }}</h3>
                                    <p class="text-sm text-gray-600">{{ $business['address'] }}, {{ $business['city'] }}, {{ $business['state'] }}</p>
                                    @if($business['phone'])
                                        <p class="text-sm text-gray-600">{{ $business['phone'] }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    @if($business['rating'])
                                        <div class="flex items-center">
                                            <span class="text-yellow-400">★</span>
                                            <span class="ml-1 text-sm text-gray-600">{{ $business['rating'] }}</span>
                                        </div>
                                    @endif
                                    <p class="text-sm text-gray-600">{{ $business['review_count'] }} Yelp reviews</p>
                                </div>
                            </div>
                            <button
                                wire:click="selectBusiness('{{ $business['id'] }}')"
                                wire:loading.attr="disabled"
                                wire:target="selectBusiness('{{ $business['id'] }}')"
                                class="w-full bg-green-700 text-white px-4 py-2 rounded-md hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center justify-center"
                            >
                                <span wire:loading.class="hidden" wire:target="selectBusiness('{{ $business['id'] }}')">Select This Company</span>
                                <span wire:loading.class="flex" wire:loading.class.remove="hidden" wire:target="selectBusiness('{{ $business['id'] }}')" class="hidden items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Job Type Results -->
        @if($showResults && !empty($jobTypeResults) && $searchMode === 'job_type')
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                        {{ $jobTypeResults['job_type']['name'] }} Contractors in {{ $jobTypeCity }}{{ $jobTypeState ? ', ' . $jobTypeState : '' }}
                    </h2>
                    <p class="text-gray-600">{{ $jobTypeResults['total_found'] }} contractors found within {{ $jobTypeRadius }} miles</p>
                </div>

                @if($jobTypeResults['total_found'] === 0)
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709M15 6.291A7.962 7.962 0 0012 5c-2.34 0-4.29 1.009-5.824 2.709" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No contractors found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            We couldn't find any {{ $jobTypeResults['job_type']['name'] }} contractors within {{ $jobTypeRadius }} miles of {{ $jobTypeCity }}{{ $jobTypeState ? ', ' . $jobTypeState : '' }}.
                        </p>
                        <p class="mt-2 text-sm text-gray-500">
                            Try searching in a nearby city or check back later.
                        </p>
                    </div>
                @else

                <div class="space-y-6">
                    @foreach($jobTypeResults['companies'] as $result)
                        @php $company = $result['company']; $score = $result['score']; @endphp
                        <div class="border border-gray-200 rounded-lg p-6">
                            <!-- Company Header -->
                            <div class="border-b border-gray-200 pb-4 mb-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900">{{ $company->name }}</h3>
                                        <p class="text-gray-600">{{ $company->city }}{{ $company->state ? ', ' . $company->state : '' }}</p>
                                        @if($company->license_number)
                                            <p class="text-sm text-gray-600 mt-1">
                                                License: {{ $company->license_number }}
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ in_array(strtoupper($company->license_status), ['CLEAR', 'ACTIVE', 'VALID', 'CURRENT']) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $company->license_status }}
                                                </span>
                                            </p>
                                        @endif
                                        @if($company->website)
                                            <p class="text-sm text-gray-600 mt-1">
                                                <a href="{{ $company->website }}" target="_blank" class="text-green-700 hover:text-green-800 underline">
                                                    Visit Website
                                                </a>
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <div class="text-3xl font-bold text-green-700">{{ number_format($company->score, 1) }}</div>
                                        <div class="text-sm text-gray-600">Overall Score</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Score Breakdown -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <h4 class="font-semibold text-gray-900 mb-1">Review Score</h4>
                                    <div class="text-xl font-bold text-green-700">{{ number_format($score->review_score, 1) }}</div>
                                    <p class="text-xs text-gray-600">{{ $company->total_reviews }} total reviews</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <h4 class="font-semibold text-gray-900 mb-1">License Score</h4>
                                    <div class="text-xl font-bold text-green-700">{{ number_format($score->license_score, 1) }}</div>
                                    <p class="text-xs text-gray-600">{{ ucfirst($company->license_status ?? 'Unknown') }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <h4 class="font-semibold text-gray-900 mb-1">Review Count Score</h4>
                                    <div class="text-xl font-bold text-purple-600">{{ number_format($score->volume_score, 1) }}</div>
                                    <p class="text-xs text-gray-600">{{ $company->total_reviews }} total reviews</p>
                                </div>
                            </div>

                            <!-- Pros and Cons -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                        <span class="text-green-500 mr-2">✓</span>
                                        Top Pros
                                    </h4>
                                    <ul class="space-y-1">
                                        @if($company->pros)
                                            @foreach(array_slice($company->pros, 0, 3) as $pro)
                                                <li class="flex items-start text-sm">
                                                    <span class="text-green-500 mr-2 mt-1">•</span>
                                                    <span class="text-gray-700">{{ $pro }}</span>
                                                </li>
                                            @endforeach
                                        @else
                                            <li class="text-gray-500 text-sm">No pros available</li>
                                        @endif
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                        <span class="text-red-500 mr-2">✗</span>
                                        Top Cons
                                    </h4>
                                    <ul class="space-y-1">
                                        @if($company->cons)
                                            @foreach(array_slice($company->cons, 0, 3) as $con)
                                                <li class="flex items-start text-sm">
                                                    <span class="text-red-500 mr-2 mt-1">•</span>
                                                    <span class="text-gray-700">{{ $con }}</span>
                                                </li>
                                            @endforeach
                                        @else
                                            <li class="text-gray-500 text-sm">No cons available</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <!-- Get Full Analysis Link -->
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="text-center">
                                    <button
                                        wire:click="searchCompany('{{ addslashes($company->name) }}', '{{ addslashes($company->city) }}', '{{ addslashes($company->state ?? '') }}')"
                                        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 text-sm font-medium transition-colors duration-200"
                                    >
                                        Get Full Analysis
                                    </button>
                                    <p class="text-xs text-gray-500 mt-1">Get detailed reviews, license info, and comprehensive scoring</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif

                <!-- Reset Button -->
                <div class="mt-8 text-center">
                    <button
                        wire:click="resetSearch"
                        class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                    >
                        Search Again
                    </button>
                </div>
            </div>
        @endif

        <!-- Company Results -->
        @if($showResults && $results && $searchMode === 'company')
            <div class="bg-white rounded-lg shadow-md p-6">
                @php $company = $results['company']; $score = $results['score']; @endphp

                <!-- Company Header -->
                <div class="border-b border-gray-200 pb-6 mb-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $company->name }}</h2>
                            <p class="text-gray-600">{{ $company->city }}{{ $company->state ? ', ' . $company->state : '' }}</p>
                            @if($company->license_number)
                                <p class="text-sm text-gray-600 mt-1">
                                    License: {{ $company->license_number }}
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ in_array(strtoupper($company->license_status), ['CLEAR', 'ACTIVE', 'VALID', 'CURRENT']) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $company->license_status }}
                                    </span>
                                </p>
                            @endif
                            @if($company->website)
                                <p class="text-sm text-gray-600 mt-1">
                                    <a href="{{ $company->website }}" target="_blank" class="text-green-700 hover:text-green-800 underline">
                                        Visit Website
                                    </a>
                                </p>
                            @endif
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-green-700">{{ number_format($company->score, 1) }}</div>
                            <div class="text-sm text-gray-600">Overall Score</div>
                        </div>
                    </div>
                </div>

                <!-- Score Breakdown -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Review Score</h3>
                        <div class="text-2xl font-bold text-green-700">{{ number_format($score->review_score, 1) }}</div>
                        <p class="text-sm text-gray-600">{{ $company->total_reviews }} total reviews</p>
                        @if($company->average_rating)
                            <p class="text-sm text-gray-600">{{ number_format($company->average_rating, 1) }}/5 average rating</p>
                        @endif
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-900 mb-2">License Score</h3>
                        <div class="text-2xl font-bold text-green-700">{{ number_format($score->license_score, 1) }}</div>
                        <p class="text-sm text-gray-600">{{ ucfirst($company->license_status ?? 'Unknown') }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Review Count Score </h3>
                        <div class="text-2xl font-bold text-purple-600">{{ number_format($score->volume_score, 1) }}</div>
                        <p class="text-sm text-gray-600">{{ $company->total_reviews }} total reviews</p>
                    </div>
                </div>

                <!-- Pros and Cons -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="text-green-500 mr-2">✓</span>
                            Top Pros
                        </h3>
                        <ul class="space-y-2">
                            @if($company->pros)
                                @foreach($company->pros as $pro)
                                    <li class="flex items-start">
                                        <span class="text-green-500 mr-2 mt-1">•</span>
                                        <span class="text-gray-700">{{ $pro }}</span>
                                    </li>
                                @endforeach
                            @else
                                <li class="text-gray-500">No pros available</li>
                            @endif
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="text-red-500 mr-2">✗</span>
                            Top Cons
                        </h3>
                        <ul class="space-y-2">
                            @if($company->cons)
                                @foreach($company->cons as $con)
                                    <li class="flex items-start">
                                        <span class="text-red-500 mr-2 mt-1">•</span>
                                        <span class="text-gray-700">{{ $con }}</span>
                                    </li>
                                @endforeach
                            @else
                                <li class="text-gray-500">No cons available</li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- License Information -->
                @if($company->license)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="text-green-500 mr-2">📋</span>
                            License Information
                        </h3>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">License Number</dt>
                                    <dd class="text-sm text-gray-900 font-semibold">{{ $company->license->license_no }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ in_array(strtoupper($company->license->primary_status), ['CLEAR', 'ACTIVE', 'VALID', 'CURRENT']) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $company->license->primary_status }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Business Type</dt>
                                    <dd class="text-sm text-gray-900">{{ $company->license->business_type }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Issue Date</dt>
                                    <dd class="text-sm text-gray-900">{{ $company->license->issue_date ? $company->license->issue_date->format('M d, Y') : 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Classifications</dt>
                                    <dd class="text-sm text-gray-900">{{ $company->license->classifications ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Business Phone</dt>
                                    <dd class="text-sm text-gray-900">{{ $company->license->business_phone ?? 'N/A' }}</dd>
                                </div>
                                <div class="md:col-span-2 lg:col-span-3">
                                    <dt class="text-sm font-medium text-gray-500">Mailing Address</dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ $company->license->mailing_address }}<br>
                                        {{ $company->license->city }}, {{ $company->license->state }} {{ $company->license->zip_code }}<br>
                                        {{ $company->license->county }} County
                                    </dd>
                                </div>
                                @if($company->license->workers_comp_coverage_type)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Workers Comp Coverage</dt>
                                        <dd class="text-sm text-gray-900">{{ $company->license->workers_comp_coverage_type }}</dd>
                                    </div>
                                @endif
                                @if($company->license->wc_insurance_company)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Workers Comp Company</dt>
                                        <dd class="text-sm text-gray-900">{{ $company->license->wc_insurance_company }}</dd>
                                    </div>
                                @endif
                                @if($company->license->cb_surety_company)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Contractor's Bond</dt>
                                        <dd class="text-sm text-gray-900">{{ $company->license->cb_surety_company }}</dd>
                                    </div>
                                @endif
                                @if($company->license->cb_effective_date)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Bond Effective Date</dt>
                                        <dd class="text-sm text-gray-900">{{ $company->license->cb_effective_date->format('M d, Y') }}</dd>
                                    </div>
                                @endif
                                @if($company->license->cb_amount)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Bond Amount</dt>
                                        <dd class="text-sm text-gray-900">${{ number_format($company->license->cb_amount, 2) }}</dd>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- AI Analysis Report -->
                @if($company->ai_report_available && $company->ai_report_markdown)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="text-blue-500 mr-2">🤖</span>
                            AI Analysis Report
                        </h3>
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 border border-blue-200">
                            <div class="prose prose-sm max-w-none">
                                {!! \Illuminate\Support\Str::markdown($company->ai_report_markdown) !!}
                            </div>
                            @if($company->ai_report_generated_at)
                                <div class="mt-4 pt-4 border-t border-blue-200">
                                    <p class="text-xs text-blue-600">
                                        <span class="font-medium">Report generated:</span>
                                        {{ $company->ai_report_generated_at->format('M j, Y \a\t g:i A') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @elseif($company->ai_report_available && (!empty($company->pros) || !empty($company->cons)))
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="text-blue-500 mr-2">🤖</span>
                            AI Analysis Report
                        </h3>
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 border border-blue-200">
                            <div class="text-sm text-gray-700">
                                <p class="mb-4">Based on customer reviews, our AI analysis has identified the following key points:</p>

                                @if(!empty($company->pros))
                                    <div class="mb-4">
                                        <h4 class="font-medium text-green-700 mb-2">Key Strengths:</h4>
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach($company->pros as $pro)
                                                <li class="text-green-600">{{ $pro }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if(!empty($company->cons))
                                    <div>
                                        <h4 class="font-medium text-red-700 mb-2">Areas for Improvement:</h4>
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach($company->cons as $con)
                                                <li class="text-red-600">{{ $con }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            @if($company->ai_report_generated_at)
                                <div class="mt-4 pt-4 border-t border-blue-200">
                                    <p class="text-xs text-blue-600">
                                        <span class="font-medium">Report generated:</span>
                                        {{ $company->ai_report_generated_at->format('M j, Y \a\t g:i A') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @elseif($company->ai_report_available === false)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="text-gray-400 mr-2">🤖</span>
                            AI Analysis Report
                        </h3>
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709M15 6.291A7.962 7.962 0 0012 5c-2.34 0-4.29 1.009-5.824 2.709" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">AI Analysis Unavailable</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    We encountered an issue generating the AI analysis report for this company.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Recent Reviews -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Reviews</h3>
                    <div class="space-y-4">
                        @forelse($company->reviews->take(5) as $review)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center">
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-sm text-gray-600">{{ $review->rating }}/5</span>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $review->platform }} • {{ $review->review_date?->format('M j, Y') }}
                                    </div>
                                </div>
                                <p class="text-gray-700">{{ Str::limit($review->content, 200) }}</p>
                                @if($review->reviewer_name)
                                    <p class="text-sm text-gray-500 mt-2">- {{ $review->reviewer_name }}</p>
                                @endif
                            </div>
                        @empty
                            <p class="text-gray-500">No reviews available</p>
                        @endforelse
                    </div>
                </div>

                <!-- Reset Button -->
                <div class="mt-8 text-center">
                    <button
                        wire:click="resetSearch"
                        class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                    >
                        Search Another Company
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
