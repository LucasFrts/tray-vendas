<?php

namespace Tests\Feature\Repositories;

use App\Models\Order;
use App\Models\Seller;
use App\Repositories\OrderRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected OrderRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new OrderRepository();
    }

    public function test_create_order()
    {
        $seller = Seller::factory()->create();

        $data = [
            'seller_id' => $seller->id,
            'date' => now()->toDateString(),
            'amount' => 1000,
        ];

        $order = $this->repository->create($data);

        $this->assertDatabaseHas('orders', $data);
        $this->assertEquals($data['amount'], $order->amount);
    }

    public function test_find_order()
    {
        $order = Order::factory()->create();

        $found = $this->repository->find($order->id);

        $this->assertEquals($order->id, $found->id);
    }

    public function test_update_order()
    {
        $order = Order::factory()->create();
        $updatedData = ['amount' => 99999];

        $this->repository->update($order->id, $updatedData);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'amount' => 99999,
        ]);
    }

    public function test_delete_order()
    {
        $order = Order::factory()->create();

        $this->repository->delete($order->id);

        $this->assertSoftDeleted('orders', [
            'id' => $order->id,
        ]);
    }

    public function test_all_orders()
    {
        Order::factory()->count(3)->create();

        $orders = $this->repository->all();

        $this->assertCount(3, $orders);
    }

    public function test_paginate_orders()
    {
        Order::factory()->count(20)->create();

        $paginated = $this->repository->paginate(5, 1);

        $this->assertEquals(5, $paginated->count());
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $paginated);
    }
}
