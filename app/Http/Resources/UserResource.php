<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'anonymous_handle' => $this->anonymous_handle,
            'role' => $this->getRoleNames()->first(),
            'doctor' => $this->when(
                $this->relationLoaded('doctor') && $this->doctor,
                fn () => new DoctorResource($this->doctor)
            ),
            'created_at' => $this->created_at,
        ];
    }
}
