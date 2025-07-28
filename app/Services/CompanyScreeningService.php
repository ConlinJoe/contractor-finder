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
        private ScoringService $scoringService
    ) {}

    public function screenCompany(string $name, string $city, string $state = null): array
    {
        try {
            // Step 1: Search for the company on Yelp
            $yelpBusinesses = $this->yelpService->searchBusiness($name, $city, $state);

            if (empty($yelpBusinesses)) {
                return [
                    'success' => false,
                    'message' => 'No businesses found with that name and location.',
                    'companies' => []
                ];
            }

            // Step 2: If multiple businesses found, return them for selection
            if (count($yelpBusinesses) > 1) {
                return [
                    'success' => true,
                    'multiple_found' => true,
                    'companies' => $this->formatBusinessesForSelection($yelpBusinesses)
                ];
            }

            // Step 3: Process the single business found
            $yelpBusiness = $yelpBusinesses[0];
            return $this->processSingleBusiness($yelpBusiness, $name, $city, $state);

        } catch (\Exception $e) {
            Log::error('Company screening error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'An error occurred while screening the company.',
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
                    'message' => 'Unable to retrieve business details.',
                ];
            }

            return $this->processSingleBusiness($yelpBusiness, $name, $city, $state);

        } catch (\Exception $e) {
            Log::error('Process selected business error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'An error occurred while processing the selected business.',
            ];
        }
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
            if (!empty($googleBusinesses)) {
                $googleBusiness = $googleBusinesses[0];
                $googleReviews = $this->googlePlacesService->getBusinessReviews($googleBusiness['place_id']);
                $this->saveReviews($company, $googleReviews, 'google');

                // Update company with Google Place ID
                $company->update(['google_place_id' => $googleBusiness['place_id']]);
            }

            // Check license status using OpenAI
            $licenseInfo = $this->openAIService->checkLicenseStatus($name, $city, $state);
            $company->update([
                'license_status' => $licenseInfo['status'],
                'license_number' => $licenseInfo['license_number'],
            ]);

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
                'company' => $company->load(['reviews', 'latestScore']),
                'score' => $score,
            ];
        });
    }

    private function saveReviews(Company $company, array $reviews, string $platform): void
    {
        foreach ($reviews as $review) {
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
                    'review_date' => isset($review['time_created']) ? date('Y-m-d H:i:s', $review['time_created']) : null,
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
