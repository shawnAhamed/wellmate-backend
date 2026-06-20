<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'body' => ['sometimes', 'required', 'string', 'min:20'],
            'category' => ['sometimes', 'nullable', 'string', 'max:50'],
            'cover_image' => ['sometimes', 'nullable', 'string', 'max:2048'],
            'is_published' => ['sometimes', 'boolean'],
        ];
    }
}
