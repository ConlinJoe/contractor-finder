<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    protected $fillable = [
        'name',
        'city',
        'state',
        'license_number',
        'license_status',
        'yelp_id',
        'google_place_id',
        'website',
        'facebook_id',
        'average_rating',
        'total_reviews',
        'pros',
        'cons',
        'score',
        'last_scraped_at',
        'ai_report_markdown',
        'ai_report_json',
        'ai_report_generated_at',
        'ai_report_available',
        'job_type_id',
    ];

    protected $casts = [
        'pros' => 'array',
        'cons' => 'array',
        'average_rating' => 'decimal:2',
        'score' => 'decimal:2',
        'last_scraped_at' => 'datetime',
        'ai_report_json' => 'array',
        'ai_report_generated_at' => 'datetime',
        'ai_report_available' => 'boolean',
    ];

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(CompanyScore::class);
    }

    public function latestScore()
    {
        return $this->hasOne(CompanyScore::class)->latestOfMany('scored_at');
    }

    public function license(): HasOne
    {
        return $this->hasOne(License::class, 'license_no', 'license_number');
    }

    public function jobType(): BelongsTo
    {
        return $this->belongsTo(JobType::class);
    }
}
