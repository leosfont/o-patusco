<?php

namespace Database\Factories;

use App\Models\AnimalType;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnimalTypeFactory extends Factory
{
    protected $model = AnimalType::class;

    public function definition(): array
    {
        return [
            'type_name' => $this->faker->unique()->randomElement(['cão', 'gato', 'coelho']),
        ];
    }
}
