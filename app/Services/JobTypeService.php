<?php

namespace App\Services;

use App\Models\JobType;
use App\Models\Company;
use App\Services\YelpService;
use App\Services\GooglePlacesService;
use App\Services\CompanyScreeningService;
use Illuminate\Support\Facades\Log;

class JobTypeService
{
    private YelpService $yelpService;
    private GooglePlacesService $googlePlacesService;
    private CompanyScreeningService $companyScreeningService;

    public function __construct(
        YelpService $yelpService,
        GooglePlacesService $googlePlacesService,
        CompanyScreeningService $companyScreeningService
    ) {
        $this->yelpService = $yelpService;
        $this->googlePlacesService = $googlePlacesService;
        $this->companyScreeningService = $companyScreeningService;
    }

    /**
     * Get all active job types
     */
    public function getJobTypes(): array
    {
        return JobType::active()
            ->ordered()
            ->get()
            ->toArray();
    }

    /**
     * Get job types by category
     */
    public function getJobTypesByCategory(string $category): array
    {
        return JobType::active()
            ->byCategory($category)
            ->ordered()
            ->get()
            ->toArray();
    }

    /**
     * Search for job types by name or keywords
     */
    public function searchJobTypes(string $search): array
    {
        return JobType::active()
            ->search($search)
            ->ordered()
            ->get()
            ->toArray();
    }

    /**
     * Find contractors by job type and location
     */
    public function findContractorsByJobType(int $jobTypeId, string $city, ?string $state = null, int $radiusMiles = 10): array
    {
        $jobType = JobType::find($jobTypeId);

        if (!$jobType) {
            return [
                'success' => false,
                'message' => 'Job type not found',
                'companies' => [],
                'api_issues' => []
            ];
        }

        $apiIssues = [];
        $companies = [];

        try {
            // Search Yelp for contractors in this job type
            $yelpResults = $this->searchYelpByJobType($jobType, $city, $state, $radiusMiles);
            if ($yelpResults['success']) {
                $companies = array_merge($companies, $yelpResults['companies']);
            } else {
                $apiIssues[] = $yelpResults['message'];
            }

            // Search Google Places for contractors in this job type
            $googleResults = $this->searchGoogleByJobType($jobType, $city, $state, $radiusMiles);
            if ($googleResults['success']) {
                $companies = array_merge($companies, $googleResults['companies']);
            } else {
                $apiIssues[] = $googleResults['message'];
            }

            // Remove duplicates and merge similar companies
            $companies = $this->mergeDuplicateCompanies($companies);

            Log::info('Job type search results', [
                'job_type' => $jobType->name,
                'city' => $city,
                'radius_miles' => $radiusMiles,
                'total_companies_found' => count($companies),
                'yelp_results' => $yelpResults['success'] ? count($yelpResults['companies']) : 0,
                'google_results' => $googleResults['success'] ? count($googleResults['companies']) : 0
            ]);

            // Limit to top 10 companies to get more diverse results
            $companies = array_slice($companies, 0, 10);

            // Process each company with lightweight screening (faster approach)
            $processedCompanies = [];
            foreach ($companies as $company) {
                try {
                    // Set a timeout for each company processing
                    set_time_limit(30); // Reset timeout for each iteration

                    // Create a lightweight company record without full API calls
                    $processedCompany = $this->createLightweightCompany($company, $city, $state, $jobTypeId);

                    if ($processedCompany) {
                        $processedCompanies[] = [
                            'company' => $processedCompany,
                            'score' => $this->calculateLightweightScore($processedCompany),
                            'job_type' => $jobType
                        ];
                    }
                } catch (\Exception $e) {
                    Log::error("Error processing company {$company['name']}: " . $e->getMessage());
                    // Continue with next company instead of failing completely
                }
            }

            // Sort by score (highest first)
            usort($processedCompanies, function ($a, $b) {
                return $b['company']->score <=> $a['company']->score;
            });

            // Add AI-generated pros/cons for top 5 results
            $top5Companies = array_slice($processedCompanies, 0, 5);
            $this->addAISummariesToCompanies($top5Companies);

            return [
                'success' => true,
                'companies' => $processedCompanies,
                'job_type' => $jobType,
                'total_found' => count($processedCompanies),
                'api_issues' => $apiIssues
            ];

        } catch (\Exception $e) {
            Log::error("Error finding contractors by job type: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while searching for contractors',
                'companies' => [],
                'api_issues' => $apiIssues
            ];
        }
    }

    /**
     * Search Yelp for contractors by job type
     */
    private function searchYelpByJobType(JobType $jobType, string $city, ?string $state = null, int $radiusMiles = 10): array
    {
        try {
            // Create search terms using job type name and keywords
            $searchTerms = array_merge([$jobType->name], $jobType->keywords ?? []);
            $searchTerm = implode(' ', array_slice($searchTerms, 0, 3)); // Use first 3 terms

            $location = $state ? "{$city}, {$state}" : $city;

            $businesses = $this->yelpService->searchBusiness($searchTerm, $location, null, $radiusMiles);

            if (empty($businesses)) {
                return [
                    'success' => false,
                    'message' => 'Yelp is currently down or not responding',
                    'companies' => []
                ];
            }

            $companies = [];
            foreach ($businesses as $business) {
                $companies[] = [
                    'id' => $business['id'],
                    'name' => $business['name'],
                    'address' => $business['location']['address1'] ?? '',
                    'city' => $business['location']['city'] ?? '',
                    'state' => $business['location']['state'] ?? '',
                    'phone' => $business['phone'] ?? '',
                    'rating' => $business['rating'] ?? 0,
                    'review_count' => $business['review_count'] ?? 0,
                    'platform' => 'yelp'
                ];
            }

            return [
                'success' => true,
                'companies' => $companies
            ];

        } catch (\Exception $e) {
            Log::error("Yelp search error for job type {$jobType->name}: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Yelp search failed',
                'companies' => []
            ];
        }
    }

    /**
     * Search Google Places for contractors by job type
     */
    private function searchGoogleByJobType(JobType $jobType, string $city, ?string $state = null, int $radiusMiles = 10): array
    {
        try {
            // Create search terms using job type name and keywords
            $searchTerms = array_merge([$jobType->name], $jobType->keywords ?? []);
            $searchTerm = implode(' ', array_slice($searchTerms, 0, 3)); // Use first 3 terms

            $location = $state ? "{$city}, {$state}" : $city;

            $businesses = $this->googlePlacesService->searchBusiness($searchTerm, $location, null, $radiusMiles);

            if (empty($businesses)) {
                return [
                    'success' => false,
                    'message' => 'Google Places is currently down or not responding',
                    'companies' => []
                ];
            }

            $companies = [];
            foreach ($businesses as $business) {
                $companies[] = [
                    'id' => $business['place_id'],
                    'name' => $business['name'],
                    'address' => $business['formatted_address'] ?? '',
                    'city' => $city,
                    'state' => $state,
                    'phone' => $business['formatted_phone_number'] ?? '',
                    'rating' => $business['rating'] ?? 0,
                    'review_count' => $business['user_ratings_total'] ?? 0,
                    'platform' => 'google'
                ];
            }

            return [
                'success' => true,
                'companies' => $companies
            ];

        } catch (\Exception $e) {
            Log::error("Google Places search error for job type {$jobType->name}: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Google Places search failed',
                'companies' => []
            ];
        }
    }

    /**
     * Merge duplicate companies from different platforms
     */
    private function mergeDuplicateCompanies(array $companies): array
    {
        $merged = [];
        $seen = [];

        foreach ($companies as $company) {
            $key = strtolower(trim($company['name']));

            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $merged[] = $company;
            } else {
                // Find the existing company and merge data
                $existingIndex = null;
                foreach ($merged as $index => $existing) {
                    if (strtolower(trim($existing['name'])) === $key) {
                        $existingIndex = $index;
                        break;
                    }
                }

                if ($existingIndex !== null) {
                    // Merge review counts and ratings
                    $merged[$existingIndex]['review_count'] += $company['review_count'];
                    $merged[$existingIndex]['rating'] = ($merged[$existingIndex]['rating'] + $company['rating']) / 2;
                }
            }
        }

        return $merged;
    }

    /**
     * Get job type categories
     */
    public function getCategories(): array
    {
        return JobType::active()
            ->select('category')
            ->distinct()
            ->whereNotNull('category')
            ->orderBy('category')
            ->pluck('category')
            ->toArray();
    }

    /**
     * Create a lightweight company record without full API processing
     */
    private function createLightweightCompany(array $companyData, string $city, ?string $state, int $jobTypeId): ?\App\Models\Company
    {
        try {
            // Check if company already exists
            $existingCompany = \App\Models\Company::where('name', $companyData['name'])
                ->where('city', $city)
                ->first();

            if ($existingCompany) {
                $existingCompany->job_type_id = $jobTypeId;
                // Update API IDs if not already set
                if (!$existingCompany->yelp_id && ($companyData['platform'] ?? '') === 'yelp') {
                    $existingCompany->yelp_id = $companyData['id'];
                }
                if (!$existingCompany->google_place_id && ($companyData['platform'] ?? '') === 'google') {
                    $existingCompany->google_place_id = $companyData['id'];
                }
                $existingCompany->save();
                return $existingCompany;
            }

            // Create new lightweight company record
            $company = \App\Models\Company::create([
                'name' => $companyData['name'],
                'city' => $city,
                'state' => $state,
                'job_type_id' => $jobTypeId,
                'average_rating' => $companyData['rating'] ?? 0,
                'total_reviews' => $companyData['review_count'] ?? 0,
                'score' => 0, // Will be calculated separately
                'last_scraped_at' => now(),
                // Store API IDs based on platform
                'yelp_id' => ($companyData['platform'] ?? '') === 'yelp' ? $companyData['id'] : null,
                'google_place_id' => ($companyData['platform'] ?? '') === 'google' ? $companyData['id'] : null,
            ]);

            return $company;
        } catch (\Exception $e) {
            Log::error("Error creating lightweight company: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Calculate a lightweight score based on available data
     */
    private function calculateLightweightScore(\App\Models\Company $company): \App\Models\CompanyScore
    {
        // Simple scoring based on available data
        $reviewScore = $company->average_rating ? ($company->average_rating / 5) * 100 : 50;
        $volumeScore = min(($company->total_reviews / 100) * 100, 100); // Cap at 100
        $licenseScore = 50; // Default neutral score

        $overallScore = ($reviewScore * 0.4) + ($volumeScore * 0.3) + ($licenseScore * 0.3);

        // Create a score record
        $score = \App\Models\CompanyScore::create([
            'company_id' => $company->id,
            'overall_score' => $overallScore,
            'review_score' => $reviewScore,
            'license_score' => $licenseScore,
            'volume_score' => $volumeScore,
            'score_breakdown' => [
                'review_score' => $reviewScore,
                'license_score' => $licenseScore,
                'volume_score' => $volumeScore,
            ],
            'scored_at' => now(),
        ]);

        // Update company with overall score
        $company->update(['score' => $overallScore]);

        return $score;
    }

    /**
     * Add AI-generated pros/cons summaries to companies
     */
    private function addAISummariesToCompanies(array &$companies): void
    {
        Log::info('Starting AI summary processing for ' . count($companies) . ' companies');

        foreach ($companies as &$result) {
            try {
                $company = $result['company'];
                Log::info("Processing AI summary for company: {$company->name}");

                // Get reviews from APIs for this company
                $reviews = $this->getCompanyReviews($company);
                Log::info("Found " . count($reviews) . " reviews for {$company->name}");

                if (!empty($reviews)) {
                    // Generate pros/cons using OpenAI
                    $openAIService = app(OpenAIService::class);
                    $summarizedReviews = $openAIService->summarizeReviews($reviews);

                    Log::info("AI summary generated for {$company->name}", [
                        'pros_count' => count($summarizedReviews['pros'] ?? []),
                        'cons_count' => count($summarizedReviews['cons'] ?? [])
                    ]);

                    // Update company with pros/cons
                    $company->update([
                        'pros' => $summarizedReviews['pros'],
                        'cons' => $summarizedReviews['cons'],
                        'ai_report_available' => true,
                        'ai_report_generated_at' => now(),
                    ]);

                    // Refresh the company model to get updated data
                    $company->refresh();
                } else {
                    Log::warning("No reviews found for {$company->name}, skipping AI summary");
                }
            } catch (\Exception $e) {
                Log::error("Error adding AI summary for company {$result['company']->name}: " . $e->getMessage());
                // Continue with next company instead of failing completely
            }
        }

        Log::info('Completed AI summary processing');
    }

    /**
     * Get reviews for a company from APIs
     */
    private function getCompanyReviews(\App\Models\Company $company): array
    {
        $reviews = [];

        try {
            Log::info("Getting reviews for {$company->name}", [
                'yelp_id' => $company->yelp_id,
                'google_place_id' => $company->google_place_id
            ]);

            // Get Yelp reviews if we have a Yelp ID
            if ($company->yelp_id) {
                Log::info("Fetching Yelp reviews for {$company->name}");
                $yelpReviews = $this->yelpService->getBusinessReviews($company->yelp_id);
                Log::info("Found " . count($yelpReviews) . " Yelp reviews for {$company->name}");

                foreach ($yelpReviews as $review) {
                    $reviews[] = [
                        'platform' => 'yelp',
                        'rating' => $review['rating'],
                        'content' => $review['text'] ?? '', // OpenAI expects 'content' not 'text'
                        'user' => ['name' => $review['user']['name'] ?? 'Anonymous'],
                        'time_created' => $review['time_created'] ?? null,
                    ];
                }
            }

            // Get Google reviews if we have a Google Place ID
            if ($company->google_place_id) {
                Log::info("Fetching Google reviews for {$company->name}");
                $googleReviews = $this->googlePlacesService->getBusinessReviews($company->google_place_id);
                Log::info("Found " . count($googleReviews) . " Google reviews for {$company->name}");

                foreach ($googleReviews as $review) {
                    $reviews[] = [
                        'platform' => 'google',
                        'rating' => $review['rating'],
                        'content' => $review['text'] ?? '', // OpenAI expects 'content' not 'text'
                        'author_name' => $review['author_name'] ?? 'Anonymous',
                        'time' => $review['time'] ?? null,
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error("Error getting reviews for company {$company->name}: " . $e->getMessage());
        }

        Log::info("Total reviews collected for {$company->name}: " . count($reviews));
        return $reviews;
    }
}
