<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->token = $user->createToken('TestToken')->plainTextToken;
    }

    public function test_can_list_users()
    {
        User::factory(5)->create();

        $response = $this->withToken($this->token)
            ->getJson('/api/users');

        $response->assertOk();
        $response->assertJsonStructure(['data']);
    }

    public function test_can_show_user()
    {
        $user = User::factory()->create();

        $response = $this->withToken($this->token)
            ->getJson("/api/users/{$user->id}");

        $response->assertOk();
        $response->assertJsonFragment(['email' => $user->email]);
    }

    public function test_can_update_user()
    {
        $user = User::factory()->create();

        $payload = ['name' => 'Updated Name'];

        $response = $this->withToken($this->token)
            ->putJson("/api/users/{$user->id}", $payload);

        $response->assertOk();
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
    }

    public function test_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->withToken($this->token)
            ->deleteJson("/api/users/{$user->id}");

        $response->assertNoContent();
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }
}