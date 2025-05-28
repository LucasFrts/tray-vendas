<?php

namespace Tests\Unit\Services;

use App\Repositories\Contracts\SellerRepositoryInterface;
use App\Services\SellerService;
use Illuminate\Contracts\Hashing\Hasher;
use PHPUnit\Framework\TestCase;
use Mockery;

class SellerServiceTest extends TestCase
{
    protected $sellerRepository;
    protected SellerService $sellerService;
    private $hasher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->hasher = Mockery::mock(Hasher::class);
        $this->sellerRepository = Mockery::mock(SellerRepositoryInterface::class);
        $this->sellerService = new SellerService($this->sellerRepository, $this->hasher);
    }

    public function test_get_all_paginated()
    {
        $this->sellerRepository
            ->shouldReceive('paginate')
            ->with(15, 1)
            ->andReturn(collect(['seller1', 'seller2']));

        $result = $this->sellerService->getAll(true, 15, 1);
        $this->assertEquals(['seller1', 'seller2'], $result->all());
    }

    public function test_get_all_without_pagination()
    {
        $this->sellerRepository
            ->shouldReceive('all')
            ->andReturn(collect(['seller1', 'seller2']));

        $result = $this->sellerService->getAll(false);
        $this->assertEquals(['seller1', 'seller2'], $result->all());
    }

    public function test_can_create_seller()
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

        $this->sellerRepository
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

        $user = $this->sellerService->create($data);

        $this->assertEquals('Lucas', $user->name);
        $this->assertEquals($hashedPassword, $user->password);
    }

    public function test_can_update_seller()
    {
        $data = ['name' => 'Jane Doe'];

        $this->sellerRepository
            ->shouldReceive('update')
            ->with(1, $data)
            ->andReturnTrue();

        $result = $this->sellerService->update(1, $data);
        $this->assertTrue($result);
    }

    public function test_can_delete_seller()
    {
        $this->sellerRepository
            ->shouldReceive('delete')
            ->with(1)
            ->andReturnTrue();

        $result = $this->sellerService->delete(1);
        $this->assertTrue($result);
    }

    public function test_can_find_seller_by_id()
    {
        $seller = (object) ['id' => 1, 'name' => 'Seller Test'];

        $this->sellerRepository
            ->shouldReceive('find')
            ->with(1)
            ->andReturn($seller);

        $result = $this->sellerService->find(1);
        $this->assertEquals($seller, $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
