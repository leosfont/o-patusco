<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();
    
        $this->seed();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_list_clients(): void
    {
        $user = User::factory()->create();
        $user->assignRole('receptionist');
        Sanctum::actingAs($user);
        $response = $this->getJson('/api/clients');
    
        $response->assertStatus(200);
    
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name'],
            ]
        ]);
    }
}
