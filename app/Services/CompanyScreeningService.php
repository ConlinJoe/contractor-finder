<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanyScreeningService
{
    public function __construct(
        private OpenAIService $openAIService,
        private AIAnalysisService $aiAnalysisService,
        private YelpService $yelpService,
        private GooglePlacesService $googlePlacesService,
        private ScoringService $scoringService,
        private CSLBService $cslbService
    ) {}

    public function processExistingCompany(Company $company): array
    {
        try {
            Log::info("Processing existing company: {$company->name}", [
                'company_id' => $company->id,
                'yelp_id' => $company->yelp_id,
                'google_place_id' => $company->google_place_id,
                'has_pros' => !empty($company->pros),
                'has_cons' => !empty($company->cons)
            ]);

            // Get fresh reviews from APIs
            $this->refreshCompanyData($company);

            // Look up CSLB license information if not already done
            if (empty($company->license_number) || empty($company->license_status)) {
                Log::info("Looking up CSLB license for {$company->name}");
                $license = $this->cslbService->searchLicense($company->name, $company->city, $company->state);
                if ($license) {
                    $company->update([
                        'license_number' => $license->license_no,
                        'license_status' => $license->primary_status,
                    ]);
                    Log::info("CSLB license found for {$company->name}: {$license->license_no}");
                } else {
                    Log::info("No CSLB license found for {$company->name}");
                }
            } else {
                Log::info("License information already exists for {$company->name}");
            }

            // Generate AI analysis if not already done
            if (empty($company->pros) || empty($company->cons)) {
                Log::info("Generating AI analysis for {$company->name}");
                $this->generateAIAnalysis($company);
            } else {
                Log::info("AI analysis already exists for {$company->name}");
            }

            // Calculate/update score
            $score = $this->scoringService->calculateScore($company);
            $company->update(['score' => $score['overall_score']]);

            // Return the processed company data
            return [
                'success' => true,
                'company' => $company->load(['reviews', 'latestScore', 'license']),
                'score' => $score,
                'api_issues' => []
            ];

        } catch (\Exception $e) {
            Log::error("Error processing existing company {$company->name}: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error processing company data: ' . $e->getMessage(),
                'api_issues' => []
            ];
        }
    }

    private function refreshCompanyData(Company $company): void
    {
        // Get fresh reviews from Yelp if we have a Yelp ID
        if ($company->yelp_id) {
            try {
                $yelpReviews = $this->yelpService->getBusinessReviews($company->yelp_id);
                $this->saveReviews($company, $yelpReviews, 'yelp');
            } catch (\Exception $e) {
                Log::warning("Could not refresh Yelp reviews for {$company->name}: " . $e->getMessage());
            }
        }

        // Get fresh reviews from Google if we have a Google Place ID
        if ($company->google_place_id) {
            try {
                $googleReviews = $this->googlePlacesService->getBusinessReviews($company->google_place_id);
                $this->saveReviews($company, $googleReviews, 'google');
            } catch (\Exception $e) {
                Log::warning("Could not refresh Google reviews for {$company->name}: " . $e->getMessage());
            }
        }
    }

    private function generateAIAnalysis(Company $company): void
    {
        try {
            $reviews = $company->reviews()->get()->toArray();
            Log::info("Found " . count($reviews) . " reviews for AI analysis of {$company->name}");

            if (!empty($reviews)) {
                $summarizedReviews = $this->openAIService->summarizeReviews($reviews);
                Log::info("AI analysis generated for {$company->name}", [
                    'pros_count' => count($summarizedReviews['pros'] ?? []),
                    'cons_count' => count($summarizedReviews['cons'] ?? [])
                ]);

                $company->update([
                    'pros' => $summarizedReviews['pros'],
                    'cons' => $summarizedReviews['cons'],
                    'ai_report_available' => true,
                    'ai_report_generated_at' => now(),
                ]);
            } else {
                Log::warning("No reviews found for AI analysis of {$company->name}");
            }
        } catch (\Exception $e) {
            Log::error("Could not generate AI analysis for {$company->name}: " . $e->getMessage());
        }
    }

    public function checkApiStatus(): array
    {
        $status = [
            'yelp' => $this->checkYelpApiStatus(),
            'google' => $this->checkGoogleApiStatus(),
            'openai' => $this->checkOpenAiApiStatus(),
        ];

        return $status;
    }

    private function checkYelpApiStatus(): array
    {
        $apiKey = config('services.yelp.api_key');
        if (empty($apiKey)) {
            return [
                'status' => 'not_configured',
                'message' => 'Yelp is currently unavailable'
            ];
        }

        try {
            // Try a simple API call to test the key
            $response = $this->yelpService->searchBusiness('test', 'New York', null, 10);
            return [
                'status' => 'working',
                'message' => 'Yelp API is working'
            ];
        } catch (\Exception $e) {
            Log::error('Yelp API test failed', ['error' => $e->getMessage()]);
            return [
                'status' => 'error',
                'message' => 'Yelp is currently down or not responding'
            ];
        }
    }

    private function checkGoogleApiStatus(): array
    {
        $apiKey = config('services.google.places_api_key');
        if (empty($apiKey)) {
            return [
                'status' => 'not_configured',
                'message' => 'Google Places is currently unavailable'
            ];
        }

        try {
            // Try a simple API call to test the key
            $response = $this->googlePlacesService->searchBusiness('test', 'New York', null, 10);
            return [
                'status' => 'working',
                'message' => 'Google Places API is working'
            ];
        } catch (\Exception $e) {
            Log::error('Google Places API test failed', ['error' => $e->getMessage()]);
            return [
                'status' => 'error',
                'message' => 'Google Places is currently down or not responding'
            ];
        }
    }

    private function checkOpenAiApiStatus(): array
    {
        $apiKey = config('services.openai.api_key');
        if (empty($apiKey)) {
            return [
                'status' => 'not_configured',
                'message' => 'AI analysis is currently unavailable'
            ];
        }

        return [
            'status' => 'working',
            'message' => 'OpenAI API is configured'
        ];
    }

    public function screenCompany(string $name, string $city, string $state = null): array
    {
        try {
            // Check API status first
            $apiStatus = $this->checkApiStatus();
            $hasWorkingApis = false;
            $apiMessages = [];

            foreach ($apiStatus as $service => $status) {
                if ($status['status'] === 'working') {
                    $hasWorkingApis = true;
                } else {
                    $apiMessages[] = $status['message'];
                }
            }

            if (!$hasWorkingApis) {
                return [
                    'success' => false,
                    'message' => 'Our search services are currently experiencing issues. Please try again in a few minutes.',
                    'api_issues' => $apiMessages,
                    'companies' => []
                ];
            }

            // Step 1: Search for the company on Yelp
            $yelpBusinesses = $this->yelpService->searchBusiness($name, $city, $state, 10);

            if (empty($yelpBusinesses)) {
                $message = 'No businesses found with that name and location.';
                if (!empty($apiMessages)) {
                    $message .= ' Note: Some search services are currently unavailable.';
                }
                return [
                    'success' => false,
                    'message' => $message,
                    'api_issues' => $apiMessages,
                    'companies' => []
                ];
            }

            // Step 2: If multiple businesses found, return them for selection
            if (count($yelpBusinesses) > 1) {
                return [
                    'success' => true,
                    'multiple_found' => true,
                    'companies' => $this->formatBusinessesForSelection($yelpBusinesses),
                    'api_issues' => $apiMessages
                ];
            }

            // Step 3: Process the single business found
            $yelpBusiness = $yelpBusinesses[0];
            $result = $this->processSingleBusiness($yelpBusiness, $name, $city, $state);
            $result['api_issues'] = $apiMessages;
            return $result;

        } catch (\Exception $e) {
            Log::error('Company screening error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'An error occurred while screening the company: ' . $e->getMessage(),
                'companies' => []
            ];
        }
    }

    public function processSelectedBusiness(string $yelpId, string $name, string $city, string $state = null): array
    {
        try {
            // Get business details from Yelp
            $yelpBusiness = $this->yelpService->getBusinessDetails($yelpId);

            if (!$yelpBusiness) {
                return [
                    'success' => false,
                    'message' => 'Unable to retrieve business details. Please check your Yelp API key.',
                ];
            }

            $result = $this->processSingleBusiness($yelpBusiness, $name, $city, $state);
            $result['api_issues'] = $this->getApiIssues();
            return $result;

        } catch (\Exception $e) {
            Log::error('Process selected business error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'An error occurred while processing the selected business: ' . $e->getMessage(),
            ];
        }
    }

    private function getApiIssues(): array
    {
        $apiStatus = $this->checkApiStatus();
        $issues = [];

        foreach ($apiStatus as $service => $status) {
            if ($status['status'] !== 'working') {
                $issues[] = $status['message'];
            }
        }

        return $issues;
    }

    private function processSingleBusiness(array $yelpBusiness, string $name, string $city, string $state = null): array
    {
        return DB::transaction(function () use ($yelpBusiness, $name, $city, $state) {
            // Create or update company record
            $company = Company::updateOrCreate(
                ['yelp_id' => $yelpBusiness['id']],
                [
                    'name' => $yelpBusiness['name'],
                    'city' => $city,
                    'state' => $state,
                    'yelp_id' => $yelpBusiness['id'],
                    'average_rating' => $yelpBusiness['rating'] ?? null,
                    'total_reviews' => $yelpBusiness['review_count'] ?? 0,
                    'last_scraped_at' => now(),
                ]
            );

            // Get reviews from Yelp
            $yelpReviews = $this->yelpService->getBusinessReviews($yelpBusiness['id']);
            $this->saveReviews($company, $yelpReviews, 'yelp');

            // Get reviews from Google Places
            $googleBusinesses = $this->googlePlacesService->searchBusiness($name, $city, $state, 10);
            $googleBusiness = null;
            if (!empty($googleBusinesses)) {
                $googleBusiness = $googleBusinesses[0];
                $googleReviews = $this->googlePlacesService->getBusinessReviews($googleBusiness['place_id']);
                $this->saveReviews($company, $googleReviews, 'google');

                // Get detailed business information including website
                $googleDetails = $this->googlePlacesService->getBusinessDetails($googleBusiness['place_id']);

                // Update company with Google Place ID and website
                $company->update([
                    'google_place_id' => $googleBusiness['place_id'],
                    'website' => $googleDetails['website'] ?? null,
                ]);
            }

            // Update company with combined review data
            $this->updateCompanyReviewData($company, $yelpBusiness, $googleBusiness);

            // Look up CSLB license information
            $license = $this->cslbService->searchLicense($name, $city, $state);
            if ($license) {
                $company->update([
                    'license_number' => $license->license_no,
                    'license_status' => $license->primary_status,
                ]);
            } else {
                // Fallback to OpenAI license check if no CSLB match found
                $licenseInfo = $this->openAIService->checkLicenseStatus($name, $city, $state);
                $company->update([
                    'license_status' => $licenseInfo['status'],
                    'license_number' => $licenseInfo['license_number'],
                ]);
            }

            // Summarize reviews using OpenAI
            $allReviews = $company->reviews()->get()->toArray();
            $summarizedReviews = $this->openAIService->summarizeReviews($allReviews);
            $company->update([
                'pros' => $summarizedReviews['pros'],
                'cons' => $summarizedReviews['cons'],
            ]);

            // Calculate score
            $score = $this->scoringService->calculateScore($company);
            $company->update(['score' => $score->overall_score]);

            // Generate AI analysis report
            $aiReport = $this->aiAnalysisService->generateContractorReport($company);
            if ($aiReport['success']) {
                $company->update([
                    'ai_report_markdown' => $aiReport['markdown'],
                    'ai_report_json' => $aiReport['json'],
                    'ai_report_generated_at' => $aiReport['generated_at'],
                    'ai_report_available' => true,
                ]);
            } else {
                // Log the error but don't fail the entire process
                Log::warning('AI report generation failed', [
                    'company_id' => $company->id,
                    'error' => $aiReport['error']
                ]);
            }

            return [
                'success' => true,
                'company' => $company->load(['reviews', 'latestScore', 'license']),
                'score' => $score,
                'ai_report' => $aiReport,
            ];
        });
    }

    private function updateCompanyReviewData(Company $company, array $yelpBusiness, ?array $googleBusiness): void
    {
        $totalReviews = 0;
        $totalRating = 0;
        $ratingCount = 0;

        // Add Yelp data
        if (isset($yelpBusiness['review_count']) && $yelpBusiness['review_count'] > 0) {
            $totalReviews += $yelpBusiness['review_count'];
            if (isset($yelpBusiness['rating'])) {
                $totalRating += $yelpBusiness['rating'] * $yelpBusiness['review_count'];
                $ratingCount += $yelpBusiness['review_count'];
            }
        }

        // Add Google data
        if ($googleBusiness && isset($googleBusiness['user_ratings_total']) && $googleBusiness['user_ratings_total'] > 0) {
            $totalReviews += $googleBusiness['user_ratings_total'];
            if (isset($googleBusiness['rating'])) {
                $totalRating += $googleBusiness['rating'] * $googleBusiness['user_ratings_total'];
                $ratingCount += $googleBusiness['user_ratings_total'];
            }
        }

        // Calculate combined average rating
        $averageRating = $ratingCount > 0 ? $totalRating / $ratingCount : null;


        // Update company with combined data
        $company->update([
            'total_reviews' => $totalReviews,
            'average_rating' => $averageRating ? round($averageRating, 2) : null,
        ]);
    }

    private function saveReviews(Company $company, array $reviews, string $platform): void
    {
        foreach ($reviews as $review) {
            $reviewDate = null;

            // Handle different date formats from different platforms
            if (isset($review['time_created'])) {
                if (is_numeric($review['time_created'])) {
                    // Unix timestamp
                    $reviewDate = date('Y-m-d H:i:s', $review['time_created']);
                } else {
                    // Already formatted date string
                    $reviewDate = $review['time_created'];
                }
            } elseif (isset($review['time'])) {
                // Google Places API uses 'time' field
                if (is_numeric($review['time'])) {
                    $reviewDate = date('Y-m-d H:i:s', $review['time']);
                } else {
                    $reviewDate = $review['time'];
                }
            } elseif (isset($review['date'])) {
                $reviewDate = $review['date'];
            }

            Review::updateOrCreate(
                [
                    'company_id' => $company->id,
                    'platform' => $platform,
                    'external_id' => $review['id'] ?? null,
                ],
                [
                    'reviewer_name' => $review['user']['name'] ?? $review['author_name'] ?? null,
                    'rating' => $review['rating'],
                    'content' => $review['text'] ?? $review['content'] ?? '',
                    'review_date' => $reviewDate,
                ]
            );
        }
    }

    private function formatBusinessesForSelection(array $businesses): array
    {
        return collect($businesses)->map(function ($business) {
            return [
                'id' => $business['id'],
                'name' => $business['name'],
                'address' => $business['location']['address1'] ?? '',
                'city' => $business['location']['city'] ?? '',
                'state' => $business['location']['state'] ?? '',
                'rating' => $business['rating'] ?? null,
                'review_count' => $business['review_count'] ?? 0,
                'phone' => $business['phone'] ?? '',
            ];
        })->toArray();
    }
}
