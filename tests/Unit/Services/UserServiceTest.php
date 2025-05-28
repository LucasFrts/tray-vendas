<?php

namespace Tests\Unit\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\UserService;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Facades\Hash;
use Mockery;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    protected $userRepository;
    protected UserService $service;
    protected $hasher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hasher = Mockery::mock(Hasher::class);
        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
        $this->service = new UserService($this->userRepository, $this->hasher);
    }

    public function test_can_create_user()
    {
        $data = [
            'name' => 'Lucas',
            'email' => 'lucas@example.com',
            'password' => 'secret123'
        ];

        $hashedPassword = 'hashed_password';
        $this->hasher
            ->shouldReceive('make')
            ->once()
            ->with('secret123')
            ->andReturn($hashedPassword);

        // Espera que o repositório seja chamado com a senha hasheada
        $this->userRepository
            ->shouldReceive('create')
            ->once()
            ->with([
                'name' => 'Lucas',
                'email' => 'lucas@example.com',
                'password' => $hashedPassword,
            ])
            ->andReturn((object)[
                'id' => 1,
                'name' => 'Lucas',
                'email' => 'lucas@example.com',
                'password' => $hashedPassword,
            ]);

        $user = $this->service->create($data);

        $this->assertEquals('Lucas', $user->name);
        $this->assertEquals($hashedPassword, $user->password);
    }

    public function test_can_find_user()
    {
        $user = (object)['id' => 1, 'name' => 'Lucas'];

        $this->userRepository
            ->shouldReceive('find')
            ->with(1)
            ->andReturn($user);

        $found = $this->service->find(1);

        $this->assertEquals($user, $found);
    }

    public function test_can_update_user()
    {
        $data = ['name' => 'Novo Nome'];

        $this->userRepository
            ->shouldReceive('update')
            ->with(1, $data)
            ->andReturn((object)[
                'id' => 1,
                'name' => 'Novo Nome'
            ]);

        $updated = $this->service->update(1, $data);

        $this->assertEquals('Novo Nome', $updated->name);
    }

    public function test_can_delete_user()
    {
        $this->userRepository
            ->shouldReceive('delete')
            ->with(1)
            ->andReturnTrue();

        $result = $this->service->delete(1);

        $this->assertTrue($result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
