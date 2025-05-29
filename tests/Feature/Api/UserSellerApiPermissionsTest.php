<?php

namespace Tests\Feature\Api;

use App\Models\Seller;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserApiPermissionsTest extends TestCase
{
    use RefreshDatabase;

    private string $token;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Cria um usuário e recupera o token
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('UserToken')->plainTextToken;
    }

    public function test_user_can_list_sellers()
    {
        Seller::factory()->count(3)->create();

        $response = $this->withToken($this->token)
                         ->getJson('/api/sellers');

        $response->assertOk()
                 ->assertJsonStructure(['data']);
    }

    public function test_user_can_view_any_seller()
    {
        $seller = Seller::factory()->create();

        $response = $this->withToken($this->token)
                         ->getJson("/api/sellers/{$seller->id}");

        $response->assertOk()
                 ->assertJsonFragment(['id' => $seller->id]);
    }

    public function test_user_can_create_seller()
    {
        $payload = [
            'name' => 'New Seller',
            'email' => 'new@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->withToken($this->token)
             ->postJson('/api/sellers', $payload)
             ->assertCreated()
             ->assertJsonFragment(['email' => 'new@example.com']);

        $this->assertDatabaseHas('sellers', ['email' => 'new@example.com']);
    }

    public function test_user_can_update_seller()
    {
        $seller = Seller::factory()->create();

        $this->withToken($this->token)
             ->putJson("/api/sellers/{$seller->id}", ['name' => 'Updated'])
             ->assertOk()
             ->assertJsonFragment(['message' => 'Vendedor atualizado com sucesso']);

        $this->assertDatabaseHas('sellers', ['id' => $seller->id, 'name' => 'Updated']);
    }

    public function test_user_can_delete_seller()
    {
        $seller = Seller::factory()->create();

        $this->withToken($this->token)
             ->deleteJson("/api/sellers/{$seller->id}")
             ->assertNoContent();

        $this->assertSoftDeleted('sellers', ['id' => $seller->id]);
    }
}