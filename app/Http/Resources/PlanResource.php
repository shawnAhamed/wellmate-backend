<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => (float) $this->price,
            'billing_interval' => $this->billing_interval,
            'monthly_question_limit' => $this->monthly_question_limit,
            'consultation_access' => $this->consultation_access,
        ];
    }
}
