<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\User;
use App\Models\AnimalType;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'animal_name' => $this->faker->word,
            'animal_type_id' => AnimalType::inRandomOrder()->first()->id,
            'animal_age' => $this->faker->numberBetween(1, 15),
            'symptoms' => $this->faker->sentence,
            'appointment_date' => $this->faker->date(),
            'appointment_time' => $this->faker->randomElement(['morning', 'afternoon']),
            'doctor_id' => User::factory(),
        ];
    }
}
