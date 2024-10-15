<?php

namespace Tests\Feature;

use App\Models\AnimalType;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AppointmentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Executa as seeds antes de cada teste
        $this->seed();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_an_appointment_and_register_a_user(): void
    {
        $animalType = AnimalType::factory()->create();

        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'animal_name' => 'Buddy',
            'animal_type_id' => $animalType->id,
            'animal_age' => 3,
            'symptoms' => 'Coughing and sneezing',
            'appointment_date' => '2024-10-20',
            'appointment_time' => 'morning',
        
        ];

        $response = $this->postJson('/api/appointments', $data);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'user_id',
                'animal_name',
                'animal_age',
                'symptoms',
                'appointment_date',
                'appointment_time',
                'created_at',
                'updated_at',
            ]
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);

        $this->assertDatabaseHas('appointments', [
            'animal_name' => 'Buddy',
            'animal_type_id' => $animalType->id,
            'animal_age' => 3,
            'symptoms' => 'Coughing and sneezing',
            'appointment_date' => '2024-10-20',
            'appointment_time' => 'manhã',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_list_appointments_for_authenticated_user(): void
    {
        $animalType = AnimalType::factory()->create();

        $user = User::factory()->create();
        $user->assignRole('client'); // Atribui a role 'client' ao usuário
        Sanctum::actingAs($user);

        $appointments = Appointment::factory()->count(2)->create([
            'user_id' => $user->id,
            'animal_type_id' => $animalType->id
        ]);

        $response = $this->getJson('/api/user/appointments');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id', 'animal_name', 'animal_age', 'symptoms', 'appointment_date', 'appointment_time'
                ]
            ]
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_show_an_appointment(): void
    {
        $animalType = AnimalType::factory()->create();

        $user = User::factory()->create();
        $user->assignRole('client'); // Atribui a role 'client' ao usuário
        Sanctum::actingAs($user);

        $appointment = Appointment::factory()->create([
            'user_id' => $user->id,
            'animal_type_id' => $animalType->id
        ]);

        $response = $this->getJson("/api/user/appointments/{$appointment->id}");

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'id' => $appointment->id,
                'animal_name' => $appointment->animal_name,
                'animal_age' => $appointment->animal_age,
                'symptoms' => $appointment->symptoms,
            ]
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_update_an_appointment(): void
    {
        $animalType = AnimalType::factory()->create();

        $user = User::factory()->create();
        $user->assignRole('client'); // Atribui a role 'client' ao usuário
        Sanctum::actingAs($user);

        $appointment = Appointment::factory()->create([
            'user_id' => $user->id,
            'animal_type_id' => $animalType->id
        ]);

        $data = [
            'animal_name' => 'Updated Buddy',
            'animal_age' => 4,
            'symptoms' => 'Updated symptoms',
            'appointment_date' => '2024-10-21',
            'appointment_time' => 'tarde', // Certifique-se de usar "manhã" ou "tarde"
        ];

        $response = $this->putJson("/api/user/appointments/{$appointment->id}", $data);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'animal_name' => 'Updated Buddy',
                'animal_age' => 4,
                'symptoms' => 'Updated symptoms',
            ]
        ]);

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'animal_name' => 'Updated Buddy',
            'animal_age' => 4,
            'symptoms' => 'Updated symptoms',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_delete_an_appointment(): void
    {
        $animalType = AnimalType::factory()->create();

        $user = User::factory()->create();
        $user->assignRole('receptionist'); // Atribui a role 'receptionist' ao usuário
        Sanctum::actingAs($user);

        $appointment = Appointment::factory()->create([
            'user_id' => $user->id,
            'animal_type_id' => $animalType->id
        ]);

        $response = $this->deleteJson("/api/user/appointments/{$appointment->id}");

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Appointment deleted successfully'
        ]);

        $this->assertDatabaseMissing('appointments', [
            'id' => $appointment->id,
        ]);
    }
}
