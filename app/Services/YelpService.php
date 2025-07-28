<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YelpService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.yelp.com/v3';

    public function __construct()
    {
        $this->apiKey = config('services.yelp.api_key') ?? '';

        if (empty($this->apiKey)) {
            Log::warning('Yelp API key is not configured');
        }
    }

    public function searchBusiness(string $name, string $city, string $state = null): array
    {
        if (empty($this->apiKey)) {
            Log::error('Yelp API key is not configured');
            return [];
        }

        try {
            $location = $state ? "{$city}, {$state}" : $city;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get("{$this->baseUrl}/businesses/search", [
                'term' => $name,
                'location' => $location,
                'limit' => 5,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['businesses'] ?? [];
            }

            Log::error('Yelp API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('Yelp service error', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function getBusinessReviews(string $businessId): array
    {
        if (empty($this->apiKey)) {
            Log::error('Yelp API key is not configured');
            return [];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get("{$this->baseUrl}/businesses/{$businessId}/reviews");

            if ($response->successful()) {
                $data = $response->json();
                return $data['reviews'] ?? [];
            }

            Log::error('Yelp reviews API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('Yelp reviews service error', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function getBusinessDetails(string $businessId): ?array
    {
        if (empty($this->apiKey)) {
            Log::error('Yelp API key is not configured');
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get("{$this->baseUrl}/businesses/{$businessId}");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Yelp business details API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Yelp business details service error', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
