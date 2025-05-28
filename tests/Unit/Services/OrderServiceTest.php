<?php

namespace Tests\Unit\Services;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Services\OrderService;
use Illuminate\Support\Collection;
use Mockery;
use PHPUnit\Framework\TestCase;

class OrderServiceTest extends TestCase
{
    protected $orderRepository;
    protected OrderService $orderService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orderRepository = Mockery::mock(OrderRepositoryInterface::class);
        $this->orderService = new OrderService($this->orderRepository);
    }

    public function test_get_all_without_pagination()
    {
        $orders = collect([new Order(), new Order()]);
        $this->orderRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn($orders);

        $result = $this->orderService->getAll();

        $this->assertEquals($orders, $result);
    }

    public function test_get_all_with_pagination()
    {
        $paginated = Mockery::mock('Illuminate\Pagination\Paginator');

        $this->orderRepository
            ->shouldReceive('paginate')
            ->with(15, 50)
            ->once()
            ->andReturn($paginated);

        $result = $this->orderService->getAll(true, 15, 50);

        $this->assertEquals($paginated, $result);
    }

    public function test_create_order()
    {
        $data = ['name' => 'Test Order'];
        $order = new Order($data);

        $this->orderRepository
            ->shouldReceive('create')
            ->with($data)
            ->once()
            ->andReturn($order);

        $result = $this->orderService->create($data);

        $this->assertEquals($order, $result);
    }

    public function test_update_order()
    {
        $id = 1;
        $data = ['name' => 'Updated Order'];
        $order = new Order($data);

        $this->orderRepository
            ->shouldReceive('update')
            ->with($id, $data)
            ->once()
            ->andReturn($order);

        $result = $this->orderService->update($id, $data);

        $this->assertEquals($order, $result);
    }

    public function test_delete_order()
    {
        $id = 1;

        $this->orderRepository
            ->shouldReceive('delete')
            ->with($id)
            ->once()
            ->andReturn(true);

        $result = $this->orderService->delete($id);

        $this->assertTrue($result);
    }

    public function test_find_order()
    {
        $id = 1;
        $order = new Order(['id' => $id]);

        $this->orderRepository
            ->shouldReceive('find')
            ->with($id)
            ->once()
            ->andReturn($order);

        $result = $this->orderService->find($id);

        $this->assertEquals($order, $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
