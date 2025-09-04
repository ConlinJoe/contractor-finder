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
        private YelpService $yelpService,
        private GooglePlacesService $googlePlacesService,
        private ScoringService $scoringService,
        private CSLBService $cslbService
    ) {}

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
                'message' => 'Yelp API key is not configured'
            ];
        }

        try {
            // Try a simple API call to test the key
            $response = $this->yelpService->searchBusiness('test', 'New York');
            return [
                'status' => 'working',
                'message' => 'Yelp API is working'
            ];
        } catch (\Exception $e) {
            Log::error('Yelp API test failed', ['error' => $e->getMessage()]);
            return [
                'status' => 'error',
                'message' => 'Yelp API is not responding correctly. Please check your API key.'
            ];
        }
    }

    private function checkGoogleApiStatus(): array
    {
        $apiKey = config('services.google.places_api_key');
        if (empty($apiKey)) {
            return [
                'status' => 'not_configured',
                'message' => 'Google Places API key is not configured'
            ];
        }

        try {
            // Try a simple API call to test the key
            $response = $this->googlePlacesService->searchBusiness('test', 'New York');
            return [
                'status' => 'working',
                'message' => 'Google Places API is working'
            ];
        } catch (\Exception $e) {
            Log::error('Google Places API test failed', ['error' => $e->getMessage()]);
            return [
                'status' => 'error',
                'message' => 'Google Places API is not responding correctly. Please check your API key.'
            ];
        }
    }

    private function checkOpenAiApiStatus(): array
    {
        $apiKey = config('services.openai.api_key');
        if (empty($apiKey)) {
            return [
                'status' => 'not_configured',
                'message' => 'OpenAI API key is not configured'
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
                    'message' => 'No APIs are currently working. Please check your API keys.',
                    'api_issues' => $apiMessages,
                    'companies' => []
                ];
            }

            // Step 1: Search for the company on Yelp
            $yelpBusinesses = $this->yelpService->searchBusiness($name, $city, $state);

            if (empty($yelpBusinesses)) {
                $message = 'No businesses found with that name and location.';
                if (!empty($apiMessages)) {
                    $message .= ' Note: Some APIs are not working: ' . implode(', ', $apiMessages);
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
            $googleBusinesses = $this->googlePlacesService->searchBusiness($name, $city, $state);
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

            return [
                'success' => true,
                'company' => $company->load(['reviews', 'latestScore', 'license']),
                'score' => $score,
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
