<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:20'],
            'category' => ['nullable', 'string', 'max:50'],
            'cover_image' => ['nullable', 'string', 'max:2048'],
            'is_published' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published', true),
            'category' => $this->input('category', 'general'),
        ]);
    }
}
