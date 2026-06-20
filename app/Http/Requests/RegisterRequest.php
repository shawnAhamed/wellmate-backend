<?php

namespace App\Http\Requests;

use App\Models\Doctor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'anonymous_handle' => ['nullable', 'string', 'max:50'],
            'role' => ['required', Rule::in(['user', 'doctor'])],

            // Required only when registering as a doctor.
            'specialization' => ['required_if:role,doctor', 'nullable', 'string', 'max:255'],
            'license_number' => [
                'required_if:role,doctor',
                'nullable',
                'string',
                'max:100',
                // license_number is encrypted at rest (non-deterministic ciphertext),
                // so a plain `unique:doctors,license_number` SQL check can't work —
                // compare against the deterministic hash column instead.
                function ($attribute, $value, $fail) {
                    if ($value && Doctor::where('license_number_hash', Doctor::hashLicenseNumber($value))->exists()) {
                        $fail('This license number is already registered.');
                    }
                },
            ],
            'bio' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
