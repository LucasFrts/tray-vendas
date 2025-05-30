<?php

namespace App\Repositories\Contracts;

interface OrderRepositoryInterface extends CrudInterface
{
    public function getOrderBySellerId(string $id);
    public function getDailyOrdersBySellerId(string $id);
}