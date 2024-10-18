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
        $appointment = $this->route('appointment');

        return [
            'doctor_id' => [
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($appointment) {
                    if ($value !== $appointment->doctor_id) {
                        if (!auth()->user()->hasRole('receptionist')) {
                            $fail('Você precisa ser um recepcionista para alterar o médico.');
                        }
                    }
                    $user = User::find($value);
                    if ($user && !$user->hasAnyRole(['doctor'])) {
                        $fail('O médico selecionado é inválido.');
                    }
                }
            ],
            'animal_name' => 'sometimes|required|string|max:255',
            'animal_age' => 'sometimes|required|integer|min:0',
            'animal_type_id' => 'sometimes|required|exists:animal_types,id',
            'appointment_date' => [
                'sometimes',
                'required',
                'date',
                function ($attribute, $value, $fail) use ($appointment) {
                    if ($value !== $appointment->appointment_date->format('Y-m-d')) {
                        if (strtotime($value) <= strtotime('today')) {
                            $fail('A data do agendamento deve ser posterior a hoje.');
                        }
                    }
                },
            ],            'appointment_time' => 'sometimes|required|in:morning,afternoon',
            'symptoms' => 'sometimes|required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'doctor_id.exists' => 'O usuário selecionado não foi encontrado.',
            'animal_name.required' => 'O nome do animal é obrigatório.',
            'animal_name.max' => 'O nome do animal não pode ter mais que 255 caracteres.',
            'animal_age.required' => 'A idade do animal é obrigatória.',
            'animal_age.integer' => 'A idade do animal deve ser um número inteiro.',
            'animal_age.min' => 'A idade do animal deve ser maior ou igual a 0.',
            'appointment_date.required' => 'A data da consulta é obrigatória.',
            'appointment_date.date' => 'A data da consulta deve ser uma data válida.',
            'appointment_date.after' => 'A data da consulta deve ser posterior à data de hoje.',
            'appointment_time.required' => 'O turno da consulta é obrigatório.',
            'appointment_time.in' => 'O turno da consulta deve ser manhã ou tarde.',
            'symptoms.required' => 'Os sintomas são obrigatórios.',
            'symptoms.max' => 'Os sintomas não podem ter mais que 500 caracteres.',
        ];
    }
}
