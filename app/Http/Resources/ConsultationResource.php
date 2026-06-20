<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'scheduled_at' => $this->scheduled_at,
            'duration_minutes' => $this->duration_minutes,
            'status' => $this->status,
            'cancellation_reason' => $this->cancellation_reason,
            'patient' => $this->whenLoaded('user', fn () => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ]),
            'doctor' => new DoctorResource($this->whenLoaded('doctor')),
            'created_at' => $this->created_at,
        ];
    }
}
