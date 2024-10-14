<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'sometimes|required|date|after:today',
            'appointment_time' => 'sometimes|required|in:morning,afternoon',
            'symptoms' => 'sometimes|required|string|max:500',
        ];
    }
}
