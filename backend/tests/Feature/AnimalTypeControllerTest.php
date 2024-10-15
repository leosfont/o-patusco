<?php

namespace Tests\Feature;

use App\Models\AnimalType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AnimalTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_list_animal_types(): void
    {
        $animalTypes = AnimalType::factory()->count(3)->create();

        $response = $this->getJson('/api/animal-types');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name'],
            ]
        ]);

        $animalTypes->each(function ($animalType) use ($response) {
            $response->assertJsonFragment([
                'id' => $animalType->id,
                'name' => $animalType->name,
            ]);
        });
    }
}
