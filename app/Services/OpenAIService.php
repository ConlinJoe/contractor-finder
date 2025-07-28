<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    public function summarizeReviews(array $reviews): array
    {
        if (empty(config('services.openai.api_key'))) {
            Log::error('OpenAI API key is not configured');
            return [
                'pros' => ['Unable to analyze pros - API key not configured'],
                'cons' => ['Unable to analyze cons - API key not configured'],
            ];
        }

        $reviewsText = collect($reviews)->map(function ($review) {
            return "Rating: {$review['rating']}/5 - {$review['content']}";
        })->implode("\n\n");

        $prompt = "Based on the following customer reviews, provide the top 5 pros and top 5 cons. Focus on the most frequently mentioned positive and negative aspects. Format as JSON with 'pros' and 'cons' arrays:\n\n{$reviewsText}";

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful assistant that analyzes customer reviews and provides structured feedback. Always respond with valid JSON.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.3,
            ]);

            $content = $response->choices[0]->message->content;

            // Try to extract JSON from the response
            if (preg_match('/\{.*\}/s', $content, $matches)) {
                $json = json_decode($matches[0], true);
                if ($json && isset($json['pros']) && isset($json['cons'])) {
                    return [
                        'pros' => array_slice($json['pros'], 0, 5),
                        'cons' => array_slice($json['cons'], 0, 5),
                    ];
                }
            }

            // Fallback response
            return [
                'pros' => ['Unable to analyze pros at this time'],
                'cons' => ['Unable to analyze cons at this time'],
            ];
        } catch (\Exception $e) {
            Log::error('OpenAI service error', ['error' => $e->getMessage()]);
            return [
                'pros' => ['Unable to analyze pros - service error'],
                'cons' => ['Unable to analyze cons - service error'],
            ];
        }
    }

    public function checkLicenseStatus(string $companyName, string $city, string $state = null): array
    {
        if (empty(config('services.openai.api_key'))) {
            Log::error('OpenAI API key is not configured');
            return [
                'status' => 'unknown',
                'license_number' => null,
                'details' => 'Unable to verify license status - API key not configured',
            ];
        }

        $location = $state ? "{$city}, {$state}" : $city;

        $prompt = "Check if the business '{$companyName}' in {$location} has a valid business license. Provide information about their licensing status. Format as JSON with 'status', 'license_number', and 'details' fields.";

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful assistant that checks business licensing information. Always respond with valid JSON.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.1,
            ]);

            $content = $response->choices[0]->message->content;

            // Try to extract JSON from the response
            if (preg_match('/\{.*\}/s', $content, $matches)) {
                $json = json_decode($matches[0], true);
                if ($json) {
                    return [
                        'status' => $json['status'] ?? 'unknown',
                        'license_number' => $json['license_number'] ?? null,
                        'details' => $json['details'] ?? 'Unable to verify license status',
                    ];
                }
            }

            // Fallback response
            return [
                'status' => 'unknown',
                'license_number' => null,
                'details' => 'Unable to verify license status at this time',
            ];
        } catch (\Exception $e) {
            Log::error('OpenAI license check error', ['error' => $e->getMessage()]);
            return [
                'status' => 'unknown',
                'license_number' => null,
                'details' => 'Unable to verify license status - service error',
            ];
        }
    }
}
