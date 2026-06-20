<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question_id' => $this->question_id,
            'body' => $this->body,
            'is_accepted' => $this->is_accepted,
            'votes_count' => $this->whenCounted('votes'),
            'doctor' => new DoctorResource($this->whenLoaded('doctor')),
            'created_at' => $this->created_at,
        ];
    }
}
