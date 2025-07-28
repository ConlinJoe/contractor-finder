<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'facebook_id',
        'average_rating',
        'total_reviews',
        'pros',
        'cons',
        'score',
        'last_scraped_at',
    ];

    protected $casts = [
        'pros' => 'array',
        'cons' => 'array',
        'average_rating' => 'decimal:2',
        'score' => 'decimal:2',
        'last_scraped_at' => 'datetime',
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
}
