<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoginTokenMail;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();
    
        $this->seed();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_sends_access_link_if_user_exists(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'john.doe@example.com',
        ]);

        $data = [
            'email' => 'john.doe@example.com',
        ];

        $response = $this->postJson('/api/request-access-link', $data);

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Access link sent to your email.',
        ]);

        Mail::assertSent(LoginTokenMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });

        $this->assertNotNull($user->tokens()->first());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_422_if_user_not_found(): void
    {
        Mail::fake();

        $data = [
            'email' => 'nonexistent@example.com',
        ];

        $response = $this->postJson('/api/request-access-link', $data);

        $response->assertStatus(422);

        $response->assertJson([
            'message' => 'This email is not registered in our system.',
        ]);

        Mail::assertNothingSent();
    }
}
