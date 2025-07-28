<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GooglePlacesService
{
    private string $apiKey;
    private string $baseUrl = 'https://maps.googleapis.com/maps/api/place';

    public function __construct()
    {
        $this->apiKey = config('services.google.places_api_key') ?? '';

        if (empty($this->apiKey)) {
            Log::warning('Google Places API key is not configured');
        }
    }

    public function searchBusiness(string $name, string $city, string $state = null): array
    {
        if (empty($this->apiKey)) {
            Log::error('Google Places API key is not configured');
            return [];
        }

        try {
            $location = $state ? "{$city}, {$state}" : $city;
            $query = "{$name} in {$location}";

            $response = Http::get("{$this->baseUrl}/textsearch/json", [
                'query' => $query,
                'key' => $this->apiKey,
                'type' => 'establishment',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['results'] ?? [];
            }

            Log::error('Google Places API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('Google Places service error', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function getBusinessDetails(string $placeId): ?array
    {
        if (empty($this->apiKey)) {
            Log::error('Google Places API key is not configured');
            return null;
        }

        try {
            $response = Http::get("{$this->baseUrl}/details/json", [
                'place_id' => $placeId,
                'key' => $this->apiKey,
                'fields' => 'name,rating,user_ratings_total,reviews,formatted_address,formatted_phone_number,website',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['result'] ?? null;
            }

            Log::error('Google Places details API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Google Places details service error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function getBusinessReviews(string $placeId): array
    {
        $details = $this->getBusinessDetails($placeId);
        return $details['reviews'] ?? [];
    }
}
