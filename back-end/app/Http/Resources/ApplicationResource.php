<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'cover_letter' => $this->cover_letter,
            'resume_path' => $this->resume_path,
            'status' => $this->status,
            'employer_notes' => $this->employer_notes,
            'applied_at' => $this->applied_at,
            'job' => new JobResource($this->whenLoaded('job')),
            'user' => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at,
        ];
    }
}