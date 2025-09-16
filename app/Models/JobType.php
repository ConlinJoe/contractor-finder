<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'keywords',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'keywords' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get companies that specialize in this job type
     */
    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    /**
     * Scope to get only active job types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get job types by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Search job types by name or keywords
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereJsonContains('keywords', $search);
        });
    }
}
