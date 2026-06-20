<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $authorName = $this->relationLoaded('user') && $this->user
            ? $this->user->displayNameFor($this->is_anonymous)
            : 'Anonymous User';

        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'category' => $this->category,
            'status' => $this->status,
            'is_anonymous' => $this->is_anonymous,
            'author_name' => $authorName,
            // Only the question owner or an admin may see who actually asked it.
            'is_owner' => $request->user() && $request->user()->id === $this->user_id,
            'answers_count' => $this->whenCounted('answers'),
            'votes_count' => $this->whenCounted('votes'),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'answers' => AnswerResource::collection($this->whenLoaded('answers')),
            'created_at' => $this->created_at,
        ];
    }
}
