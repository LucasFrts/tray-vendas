<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class SellerTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

     public function test_can_create_a_seller()
    {
        $payload = [
            'name' => 'Lucas Freitas',
            'email' => 'lucas@example.com',
            'password' => '123456'
        ];

        $response = $this->post('/sellers', $payload);

        $response->assertCreated()
                 ->assertJsonFragment(['message' => 'Vendedor criado com sucesso']);

        $this->assertDatabaseHas('sellers', [
            'email' => 'lucas@example.com',
            'name' => 'Lucas Freitas'
        ]);
    }
}