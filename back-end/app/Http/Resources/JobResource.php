<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'responsibilities' => $this->responsibilities,
            'location' => $this->location,
            'is_remote' => $this->is_remote,
            'salary_min' => $this->salary_min,
            'salary_max' => $this->salary_max,
            'salary_currency' => $this->salary_currency,
            'status' => $this->status,
            'published_at' => $this->published_at,
            'expires_at' => $this->expires_at,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'category' => $this->whenLoaded('category'),
            'job_type' => $this->whenLoaded('jobType'),
            'skills' => $this->whenLoaded('skills'),
            'created_at' => $this->created_at,
        ];
    }
}