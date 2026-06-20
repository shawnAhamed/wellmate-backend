<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reason' => ['required', Rule::in(['spam', 'abuse', 'misinformation', 'inappropriate', 'other'])],
            'details' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
