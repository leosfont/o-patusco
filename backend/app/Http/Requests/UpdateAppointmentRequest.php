<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'doctor_id' => [
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::find($value);
                    if ($user && !$user->hasAnyRole(['doctor'])) {
                        $fail('The selected ' . $attribute . ' must be a doctor or client.');
                    }
                }
            ],
            'animal_name' => 'sometimes|required|string|max:255',
            'animal_age' => 'sometimes|required|integer|min:0',
            'appointment_date' => 'sometimes|required|date|after:today',
            'appointment_time' => 'sometimes|required|in:morning,afternoon',
            'symptoms' => 'sometimes|required|string|max:500',
        ];
    }
}
