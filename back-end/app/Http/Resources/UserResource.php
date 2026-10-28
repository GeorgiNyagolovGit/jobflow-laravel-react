<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
            'bio' => $this->bio,
            'has_cv' => $this->has_cv,
            'cv_original_name' => $this->cv_original_name,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
        ];
    }
}
