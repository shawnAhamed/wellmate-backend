<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->whenLoaded('user', fn () => $this->user->name),
            'specialization' => $this->specialization,
            'bio' => $this->bio,
            'license_number' => $this->when(
                $request->user()?->hasRole('admin'),
                $this->license_number
            ),
            'is_verified' => $this->is_verified,
            'verified_at' => $this->verified_at,
            'created_at' => $this->created_at,
        ];
    }
}
