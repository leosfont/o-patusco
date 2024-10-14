<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAndRegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'animal_name' => 'required|string|max:255',
            'animal_type_id' => 'required|exists:animal_types,id',
            'animal_age' => 'required|integer|min:0',
            'symptoms' => 'required|string|max:500',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|in:morning,afternoon',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'You are already registered on the platform, please sign in.',
        ];
    }
}
