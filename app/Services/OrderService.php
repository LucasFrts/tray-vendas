<?php

namespace App\Services;

use App\Repositories\Contracts\OrderRepositoryInterface;

class OrderService
{
public function __construct(
        private OrderRepositoryInterface $orderRepository
    ) {}
     
    public function getAll(bool $paginate = false, int $take  = 15, int $quantity = 50)
    {
        if ($paginate) {
            return $this->orderRepository->paginate($take, $quantity);
        }
        return $this->orderRepository->all();
    }

    public function create(array $data)
    {
        return $this->orderRepository->create($data);
    }

    public function update(string $id, array $data)
    {
        return $this->orderRepository->update($id, $data);
    }

    public function delete(string $id)
    {
        return $this->orderRepository->delete($id);
    }

    public function find(string $id)
    {
        return $this->orderRepository->find($id);
    }

    public function getOrdersBySellerId(string $id)
    {
        return $this->orderRepository->getOrderBySellerId($id);
    
    }

    public function getDailyOrdersBySellerId(string $id)
    {
        return $this->orderRepository->getDailyOrdersBySellerId($id);
    }


}