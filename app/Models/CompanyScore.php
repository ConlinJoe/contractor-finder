<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyScore extends Model
{
    protected $fillable = [
        'company_id',
        'overall_score',
        'review_score',
        'license_score',
        'volume_score',
        'score_breakdown',
        'scored_at',
    ];

    protected $casts = [
        'overall_score' => 'decimal:2',
        'review_score' => 'decimal:2',
        'license_score' => 'decimal:2',
        'volume_score' => 'decimal:2',
        'score_breakdown' => 'array',
        'scored_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
