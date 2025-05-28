<?php

namespace App\Services;

use App\Repositories\Contracts\SellerRepositoryInterface;
use Illuminate\Contracts\Hashing\Hasher;

class SellerService
{
    public function __construct(
        private SellerRepositoryInterface $sellerRepository,
        private Hasher $hasher
    ) {}
     
    public function getAll(bool $paginate = false, int $take  = 15, int $quantity = 50)
    {
        if ($paginate) {
            return $this->sellerRepository->paginate($take, $quantity);
        }
        return $this->sellerRepository->all();
    }

    public function create(array $data)
    {
        return $this->sellerRepository->create(
            array_merge($data, ['password' => $this->hasher->make($data['password'])])
        );
    }

    public function update(string $id, array $data)
    {
        return $this->sellerRepository->update($id, $data);
    }

    public function delete(string $id)
    {
        return $this->sellerRepository->delete($id);
    }

    public function find(string $id)
    {
        return $this->sellerRepository->find($id);
    }
    
    public function createTokenById(string $id, $tokenName = 'api_token') : string{
        return $this->sellerRepository->createTokenById($id, $tokenName);
    }

    
}