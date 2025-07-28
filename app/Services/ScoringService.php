<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyScore;

class ScoringService
{
    public function calculateScore(Company $company): CompanyScore
    {
        $reviewScore = $this->calculateReviewScore($company);
        $licenseScore = $this->calculateLicenseScore($company);
        $volumeScore = $this->calculateVolumeScore($company);

        $overallScore = ($reviewScore * 0.5) + ($licenseScore * 0.3) + ($volumeScore * 0.2);

        $scoreBreakdown = [
            'review_score' => [
                'score' => $reviewScore,
                'weight' => 0.5,
                'factors' => [
                    'average_rating' => $company->average_rating,
                    'total_reviews' => $company->total_reviews,
                ]
            ],
            'license_score' => [
                'score' => $licenseScore,
                'weight' => 0.3,
                'factors' => [
                    'license_status' => $company->license_status,
                    'has_license' => !empty($company->license_number),
                ]
            ],
            'volume_score' => [
                'score' => $volumeScore,
                'weight' => 0.2,
                'factors' => [
                    'total_reviews' => $company->total_reviews,
                    'review_threshold' => $this->getReviewThreshold($company->total_reviews),
                ]
            ]
        ];

        return CompanyScore::create([
            'company_id' => $company->id,
            'overall_score' => round($overallScore, 2),
            'review_score' => round($reviewScore, 2),
            'license_score' => round($licenseScore, 2),
            'volume_score' => round($volumeScore, 2),
            'score_breakdown' => $scoreBreakdown,
            'scored_at' => now(),
        ]);
    }

    private function calculateReviewScore(Company $company): float
    {
        if (!$company->average_rating || $company->total_reviews == 0) {
            return 0;
        }

        // Base score from rating (0-5 scale converted to 0-100)
        $baseScore = ($company->average_rating / 5) * 100;

        // Bonus for having more reviews (up to 10 points)
        $reviewBonus = min(10, ($company->total_reviews / 50) * 10);

        return min(100, $baseScore + $reviewBonus);
    }

    private function calculateLicenseScore(Company $company): float
    {
        if (empty($company->license_number)) {
            return 0;
        }

        // Base score for having a license
        $score = 70;

        // Bonus for active status
        if (strtolower($company->license_status) === 'active') {
            $score += 30;
        } elseif (strtolower($company->license_status) === 'valid') {
            $score += 25;
        } elseif (strtolower($company->license_status) === 'current') {
            $score += 20;
        }

        return min(100, $score);
    }

    private function calculateVolumeScore(Company $company): float
    {
        $totalReviews = $company->total_reviews;

        if ($totalReviews >= 250) {
            return 100; // Maximum score for 250+ reviews
        } elseif ($totalReviews >= 100) {
            return 80;
        } elseif ($totalReviews >= 50) {
            return 60;
        } elseif ($totalReviews >= 25) {
            return 40;
        } elseif ($totalReviews >= 10) {
            return 20;
        } else {
            return 0;
        }
    }

    private function getReviewThreshold(int $totalReviews): string
    {
        if ($totalReviews >= 250) {
            return '250+ (Excellent)';
        } elseif ($totalReviews >= 100) {
            return '100-249 (Very Good)';
        } elseif ($totalReviews >= 50) {
            return '50-99 (Good)';
        } elseif ($totalReviews >= 25) {
            return '25-49 (Fair)';
        } elseif ($totalReviews >= 10) {
            return '10-24 (Limited)';
        } else {
            return '0-9 (Very Limited)';
        }
    }
}
