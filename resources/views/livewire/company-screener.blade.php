<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Company Screener</h1>
            <p class="text-lg text-gray-600">Enter a company name and location to get comprehensive reviews and scoring</p>
        </div>

        <!-- Search Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form wire:submit="search" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="companyName" class="block text-sm font-medium text-gray-700 mb-1">
                            Company Name *
                        </label>
                        <input
                            type="text"
                            id="companyName"
                            wire:model="companyName"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
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
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
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
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter state"
                        >
                    </div>
                </div>

                <div class="flex justify-center">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span wire:loading.remove>Search Company</span>
                        <span wire:loading>Searching...</span>
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

        <!-- Loading State -->
        @if($isLoading)
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                <p class="text-gray-600">Analyzing company data...</p>
                <p class="text-sm text-gray-500 mt-2">This may take a few moments</p>
            </div>
        @endif

        <!-- Business Selection -->
        @if($showBusinessSelection)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Multiple businesses found. Please select one:</h2>
                <div class="space-y-4">
                    @foreach($businesses as $business)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 cursor-pointer" wire:click="selectBusiness('{{ $business['id'] }}')">
                            <div class="flex justify-between items-start">
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
                                    <p class="text-sm text-gray-600">{{ $business['review_count'] }} reviews</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Results -->
        @if($showResults && $results)
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
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $company->license_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($company->license_status) }}
                                    </span>
                                </p>
                            @endif
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-blue-600">{{ number_format($company->score, 1) }}</div>
                            <div class="text-sm text-gray-600">Overall Score</div>
                        </div>
                    </div>
                </div>

                <!-- Score Breakdown -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Review Score</h3>
                        <div class="text-2xl font-bold text-green-600">{{ number_format($score->review_score, 1) }}</div>
                        <p class="text-sm text-gray-600">{{ $company->total_reviews }} reviews</p>
                        @if($company->average_rating)
                            <p class="text-sm text-gray-600">{{ number_format($company->average_rating, 1) }}/5 average rating</p>
                        @endif
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-900 mb-2">License Score</h3>
                        <div class="text-2xl font-bold text-blue-600">{{ number_format($score->license_score, 1) }}</div>
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
