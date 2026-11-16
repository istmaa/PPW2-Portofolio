<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status',
        'thumbnail',
        'technology',
        'project_date',
    ];

    protected function casts(): array
    {
        return [
            'project_date' => 'date',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->when($filters['technology'] ?? null, function ($q, $tech) {
                $q->where('technology', $tech);
            })
            ->when($filters['status'] ?? null, function ($q, $status) {
                $q->where('status', $status);
            });
    }

    public function scopeSortBy(Builder $query, ?string $sort): Builder
    {
        if ($sort === 'oldest') {
            return $query->orderBy('project_date', 'asc');
        }

        if ($sort === 'newest') {
            return $query->orderBy('project_date', 'desc');
        }

        return $query->latest();
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }
}
