<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFirstAppointmentRequest extends FormRequest
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
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser um texto.',
            'name.max' => 'O nome nao pode exceder 255 caracteres.',
            'user_id.required' => 'O campo usuário é obrigatório.',
            'user_id.exists' => 'O usuário selecionado não existe.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O campo email deve ser um endereço de email válido.',
            'email.max' => 'O email não pode exceder 255 caracteres.',
            'email.unique' => 'O email informado já fez um agendamento.',
            'animal_name.required' => 'O nome do animal é obrigatório.',
            'animal_name.string' => 'O nome do animal deve ser um texto.',
            'animal_name.max' => 'O nome do animal não pode exceder 255 caracteres.',
            'animal_type_id.required' => 'O tipo de animal é obrigatório.',
            'animal_type_id.exists' => 'O tipo de animal selecionado é inválido.',
            'animal_age.required' => 'A idade do animal é obrigatória.',
            'animal_age.integer' => 'A idade do animal deve ser um número inteiro.',
            'animal_age.min' => 'A idade do animal deve ser no mínimo 0.',
            'symptoms.required' => 'Os sintomas são obrigatórios.',
            'symptoms.string' => 'Os sintomas devem ser um texto.',
            'symptoms.max' => 'Os sintomas não podem exceder 500 caracteres.',
            'appointment_date.required' => 'A data do agendamento é obrigatória.',
            'appointment_date.date' => 'A data do agendamento deve ser uma data válida.',
            'appointment_date.after' => 'A data do agendamento deve ser posterior a hoje.',
            'appointment_time.required' => 'O turno é obrigatório.',
            'appointment_time.in' => 'O turno deve ser manhã ou tarde.',
        ];
    }
}
