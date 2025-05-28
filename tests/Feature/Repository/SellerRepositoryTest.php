<?php

namespace Tests\Feature\Repositories;

use App\Models\Seller;
use App\Repositories\SellerRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected SellerRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new SellerRepository();
    }

    public function test_can_create_seller()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password'
        ];

        $seller = $this->repository->create($data);

        $this->assertDatabaseHas('sellers', $data);
        $this->assertEquals($data['name'], $seller->name);
        $this->assertEquals($data['email'], $seller->email);
    }

    public function test_can_find_seller()
    {
        $seller = Seller::factory()->create();

        $found = $this->repository->find($seller->id);

        $this->assertEquals($seller->id, $found->id);
    }

    public function test_can_update_seller()
    {
        $seller = Seller::factory()->create();
        $updateData = [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com'
        ];

        $this->repository->update($seller->id, $updateData);

        $this->assertDatabaseHas('sellers', $updateData);
    }

    public function test_can_delete_seller()
    {
        $seller = Seller::factory()->create();

        $this->repository->delete($seller->id);

        $this->assertSoftDeleted('sellers', ['id' => $seller->id]);
    }

    public function test_can_get_all_sellers()
    {
        Seller::factory()->count(3)->create();

        $result = $this->repository->all();

        $this->assertCount(3, $result);
    }

    public function test_can_paginate_sellers()
    {
        Seller::factory()->count(30)->create();

        $result = $this->repository->paginate(15, 1);

        $this->assertCount(15, $result);
        $this->assertEquals(1, $result->currentPage());
    }
}
