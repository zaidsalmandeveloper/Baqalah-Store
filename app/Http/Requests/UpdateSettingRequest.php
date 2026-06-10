<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'remove_logo' => $this->boolean('remove_logo'),
        ]);
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'phone_2' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'ntn_number' => ['nullable', 'string', 'max:100'],
            'logo' => ['nullable', 'file', 'mimes:jpeg,jpg,png,webp,svg', 'max:5120'],
            'remove_logo' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'logo.mimes' => 'Logo must be a JPEG, PNG, WEBP, or SVG image.',
            'logo.max' => 'Logo size must not exceed 5MB.',
        ];
    }
}
