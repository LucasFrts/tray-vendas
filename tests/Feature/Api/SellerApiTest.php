<?php

namespace Tests\Feature\Api;

use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerApiTest extends TestCase
{
    use RefreshDatabase;

    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $seller = Seller::factory()->create();
        $this->token = $seller->createToken('TestToken')->plainTextToken;
    }

    public function test_can_list_sellers()
    {
        Seller::factory()->count(3)->create();

        $response = $this->withToken($this->token)->getJson('/api/sellers');

        $response->assertOk()
                 ->assertJsonStructure(['data']);
    }

    public function test_can_show_a_seller()
    {
        $seller = Seller::factory()->create();

        $response = $this->withToken($this->token)->getJson("/api/sellers/{$seller->id}");

        $response->assertOk()
                 ->assertJsonFragment(['id' => $seller->id]);
    }

    public function test_can_update_a_seller()
    {
        $seller = Seller::factory()->create();

        $response = $this->withToken($this->token)->putJson("/api/sellers/{$seller->id}", [
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ]);

        $response->assertOk()
                 ->assertJsonFragment(['message' => 'Vendedor atualizado com sucess']);

        $this->assertDatabaseHas('sellers', ['name' => 'Updated Name']);
    }

    public function test_can_delete_a_seller()
    {
        $seller = Seller::factory()->create();

        $response = $this->withToken($this->token)->deleteJson("/api/sellers/{$seller->id}");

        $response->assertStatus(204); // deleted
        $this->assertSoftDeleted('sellers', ['id' => $seller->id]);
    }
}
