<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

     public function test_can_create_a_user()
    {
        $payload = [
            'name' => 'Lucas Freitas',
            'email' => 'lucas@example.com',
            'password' => 'secret123'
        ];

        $response = $this->post('/users', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['message' => 'Usuário criado com sucesso']);

        $this->assertDatabaseHas('users', [
            'email' => 'lucas@example.com',
        ]);
    }
}