<?php

namespace Tests\Feature\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private UserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new UserRepository();
    }

    public function test_can_create_user()
    {
        $user = $this->repository->create([
            'name' => 'Teste',
            'email' => 'teste@exemplo.com',
            'password' => 'senha'
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', ['email' => 'teste@exemplo.com']);
    }

    public function test_can_update_user()
    {
        $user = User::factory()->create();

        $updated = $this->repository->update($user->id, ['name' => 'Novo Nome']);

        $this->assertEquals('Novo Nome', $updated->name);
    }

    public function test_can_delete_user()
    {
        $user = User::factory()->create();
        $this->repository->delete($user->id);

        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_can_paginate_users()
    {
        User::factory(20)->create();
        $results = $this->repository->paginate(5, 2);

        $this->assertCount(5, $results);
        $this->assertEquals(2, $results->currentPage());
    }
}
