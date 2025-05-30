<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Support\Repositories\BaseRepository;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    protected string $modelClass = Order::class;

    public function find(string $id)
    {
        return $this->newQuery()->find($id);
    }

    public function all()
    {
        return $this->newQuery()->get();
    }

    public function create(array $data)
    {
        return $this->newQuery()->create($data);
    }

    public function update(string $id, array $data)
    {
        $order = $this->newQuery()->find($id);
        $order->update($data);
        return $order;
    }

    public function delete(string $id)
    {
        return $this->newQuery()->find($id)->delete();
    }

    public function paginate(int $take = 15, int $quantity = 50)
    {
        return $this->getAll(true, $take, $quantity);
    }

    public function getOrderBySellerId(string $id)
    {
        return $this->newQuery()->where('seller_id', $id)->get();
    }

    public function getDailyOrdersBySellerId(string $id)
    {
        return $this->newQuery()->where('seller_id', $id)->whereDate('created_at', today())->get();
    }

    public function getTotalAmount()
    {
        return $this->newQuery()->get()->sum('amount');
    }

    public function paginateWithRelationship(int $take, int $quantity)
    {
        return $this->newQuery()->with('seller')->paginate($take, ['*'], 'page', $quantity);
    }
}
