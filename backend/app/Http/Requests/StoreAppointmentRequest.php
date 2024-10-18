<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if (auth()->user()->hasRole('client')) {
            $this->merge([
                'user_id' => auth()->id(),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::find($value);
                    if ($user && !$user->hasRole('client')) {
                        $fail('O cliente selecionado é inválido.');
                    }
                }
            ],
            'animal_name' => 'required|string|max:255',
            'animal_type_id' => 'required|exists:animal_types,id',
            'animal_age' => 'required|integer|min:0',
            'symptoms' => 'required|string|max:500',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|in:morning,afternoon',
            'doctor_id' => [
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::find($value);
                    if ($user && !$user->hasAnyRole(['doctor'])) {
                        $fail('O médico selecionado é inválido.');
                    }

                    if (auth()->user()->hasRole('doctor') && auth()->user()->id !== $value) {
                        $fail('Você não pode atualizar o agendamento de outro médico.');
                    }
                }
            ],
        ];
    }

    public function messages()
    {
        return [
            'user_id.exists' => 'O cliente selecionado não foi encontrado.',
            'doctor_id.exists' => 'O médico selecionado não foi encontrado.',
            'animal_name.required' => 'O nome do animal é obrigatório.',
            'animal_name.max' => 'O nome do animal não pode ter mais que 255 caracteres.',
            'animal_type_id.required' => 'O tipo de animal é obrigatório.',
            'animal_type_id.exists' => 'O tipo de animal selecionado não é válido.',
            'animal_age.required' => 'A idade do animal é obrigatória.',
            'animal_age.integer' => 'A idade do animal deve ser um número inteiro.',
            'animal_age.min' => 'A idade do animal deve ser maior ou igual a 0.',
            'symptoms.required' => 'Os sintomas são obrigatórios.',
            'symptoms.max' => 'Os sintomas não podem ter mais que 500 caracteres.',
            'appointment_date.required' => 'A data da consulta é obrigatória.',
            'appointment_date.date' => 'A data da consulta deve ser uma data válida.',
            'appointment_date.after' => 'A data da consulta deve ser posterior à data de hoje.',
            'appointment_time.required' => 'O turno da consulta é obrigatório.',
            'appointment_time.in' => 'O turno da consulta deve ser manhã ou tarde.',
        ];
    }
}
