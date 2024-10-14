<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'animal_name' => 'required|string|max:255',
            'animal_type_id' => 'required|exists:animal_types,id',
            'animal_age' => 'required|integer|min:0',
            'symptoms' => 'required|string|max:500',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|in:morning,afternoon',
        ];
    }
}
