<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccessLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email is required to request access.',
            'email.email' => 'Please provide a valid email address.',
            'email.exists' => 'This email is not registered in our system.',
        ];
    }
}
