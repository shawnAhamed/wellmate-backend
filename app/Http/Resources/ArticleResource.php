<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'body' => $this->body,
            'category' => $this->category,
            'cover_image' => $this->cover_image,
            'is_published' => $this->is_published,
            'doctor' => new DoctorResource($this->whenLoaded('doctor')),
            'created_at' => $this->created_at,
        ];
    }
}
