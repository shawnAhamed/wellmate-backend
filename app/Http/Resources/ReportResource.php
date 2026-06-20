<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reportable_type' => class_basename($this->reportable_type),
            'reportable_id' => $this->reportable_id,
            'reportable' => $this->when(
                $this->relationLoaded('reportable') && $this->reportable,
                fn () => $this->reportable instanceof \App\Models\Question
                    ? new QuestionResource($this->reportable)
                    : new AnswerResource($this->reportable)
            ),
            'reason' => $this->reason,
            'details' => $this->details,
            'status' => $this->status,
            'reported_by' => $this->whenLoaded('user', fn () => $this->user->name),
            'resolved_at' => $this->resolved_at,
            'created_at' => $this->created_at,
        ];
    }
}
