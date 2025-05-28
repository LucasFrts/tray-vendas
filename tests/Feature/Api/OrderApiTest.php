<?php

namespace Tests\Feature\Api;

use App\Models\Order;
use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $seller = Seller::factory()->create();
        $this->token = $seller->createToken('TestToken')->plainTextToken;
    }

    public function test_can_list_orders()
    {
        Order::factory()->count(3)->create();

        $response = $this->withToken($this->token)->getJson('/api/orders');

        $response->assertOk()
                 ->assertJsonStructure(['data']);
    }

    public function test_can_create_an_order()
    {
        $seller = Seller::factory()->create();

        $payload = [
            'seller_id' => $seller->id,
            'amount' => 100,
            'date' => now()->format('Y-m-d')
        ];

        $response = $this->withToken($this->token)->postJson('/api/orders', $payload);

        $response->assertCreated()
                 ->assertJsonFragment(['message' => 'Pedido criado com sucesso']);

        $this->assertDatabaseHas('orders', ['seller_id' => $seller->id, 'amount' => 100]);
    }

    public function test_can_show_an_order()
    {
        $order = Order::factory()->create();

        $response = $this->withToken($this->token)->getJson("/api/orders/{$order->id}");

        $response->assertOk()
                 ->assertJsonFragment(['id' => $order->id]);
    }

    public function test_can_update_an_order()
    {
        $order = Order::factory()->create();

        $response = $this->withToken($this->token)->putJson("/api/orders/{$order->id}", [
            'amount' => 200,
            'date' => now()->format('Y-m-d'),
            'seller_id' => $order->seller_id,
        ]);

        $response->assertOk()
                 ->assertJsonFragment(['message' => 'Pedido atualizado com sucess']);

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'amount' => 200]);
    }

    public function test_can_delete_an_order()
    {
        $order = Order::factory()->create();

        $response = $this->withToken($this->token)->deleteJson("/api/orders/{$order->id}");

        $response->assertStatus(204);
        $this->assertSoftDeleted('orders', ['id' => $order->id]);
    }
}
