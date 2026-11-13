<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'regex:/^[6-9]\d{9}$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'message' => ['required', 'string'],
            'product_id' => ['nullable', 'exists:products,id'],
            'product_reference' => ['nullable', 'string', 'max:255'],
            'website' => ['sometimes', 'nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name.',
            'mobile.required' => 'Please enter your mobile number.',
            'mobile.regex' => 'Please enter a valid 10-digit Indian mobile number.',
            'email.email' => 'Please enter a valid email address.',
            'message.required' => 'Please enter your message.',
            'product_id.exists' => 'The selected product does not exist.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Strip whitespace and country code from mobile if present
        if ($this->filled('mobile')) {
            $mobile = preg_replace('/\s+/', '', $this->mobile);
            $mobile = preg_replace('/^(\+91|91|0)?/', '', $mobile);
            $this->merge(['mobile' => $mobile]);
        }

        // Honeypot field should always be empty
        if ($this->filled('website')) {
            $this->merge(['website' => '']);
        }
    }
}
