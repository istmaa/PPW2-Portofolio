<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'technology' => $this->technology,
            'project_date' => $this->project_date?->format('Y-m-d'),
            'thumbnail' => $this->thumbnail ? asset('storage/' . $this->thumbnail) : null,
            'category' => $this->category?->name,
            'category_slug' => $this->category?->slug,
        ];
    }
}
