<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'doctor_id' => optional($this->doctor)->id,
            'animal_name' => $this->animal_name,
            'animal_type' => new AppointmentAnimalTypeResource($this->whenLoaded('animalType')),
            'doctor' => new AppointmentDoctorResource($this->whenLoaded('doctor')),
            'responsible' => $this->user->name,
            'animal_age' => $this->animal_age,
            'symptoms' => $this->symptoms,
            'appointment_date' => $this->appointment_date->format('d-m-Y'),
            'appointment_time' => $this->appointment_time,
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
        ];
    }
}
