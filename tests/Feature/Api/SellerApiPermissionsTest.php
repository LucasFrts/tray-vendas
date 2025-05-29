<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerApiPermissionsTest extends TestCase
{
    use RefreshDatabase;

    private string $token;
    private Seller $seller;

    protected function setUp(): void
    {
        parent::setUp();

        // Cria um seller e recupera o token
        $this->seller = Seller::factory()->create();
        $this->token = $this->seller->createToken('TestToken')->plainTextToken;
    }

    public function test_seller_cannot_list_other_sellers()
    {
        Seller::factory()->count(3)->create();

        $response = $this->withToken($this->token)
                         ->getJson('/api/sellers');

        $response->assertForbidden();
    }

    public function test_seller_can_view_only_himself()
    {
        // Cria outro seller
        $other = Seller::factory()->create();

        // Ele não vê o outro
        $this->withToken($this->token)
             ->getJson("/api/sellers/{$other->id}")
             ->assertForbidden();

        // Mas vê a si mesmo
        $this->withToken($this->token)
             ->getJson("/api/sellers/{$this->seller->id}")
             ->assertOk()
             ->assertJsonFragment(['id' => $this->seller->id]);
    }

    public function test_seller_cannot_create_another_seller()
    {
        $payload = [
            'name' => 'New Seller',
            'email' => 'new@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->withToken($this->token)
             ->postJson('/api/sellers', $payload)
             ->assertUnauthorized();

        $this->assertDatabaseMissing('sellers', ['email' => 'new@example.com']);
    }

    public function test_seller_cannot_update_other_seller()
    {
        $other = Seller::factory()->create();

        $this->withToken($this->token)
             ->putJson("/api/sellers/{$other->id}", ['name' => 'Hacked'])
             ->assertUnauthorized();

        $this->assertDatabaseMissing('sellers', ['id' => $other->id, 'name' => 'Hacked']);
    }

    public function test_seller_cannot_delete_himself()
    {
        $this->withToken($this->token)
             ->deleteJson("/api/sellers/{$this->seller->id}")
             ->assertUnauthorized();

        $this->assertDatabaseHas('sellers', ['id' => $this->seller->id]);
    }
}