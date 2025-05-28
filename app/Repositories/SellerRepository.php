<?php

namespace App\Repositories;

use App\Models\Seller;
use App\Repositories\Contracts\SellerRepositoryInterface;
use App\Support\Repositories\BaseRepository;

class SellerRepository extends BaseRepository implements SellerRepositoryInterface
{
    protected string $modelClass = Seller::class;

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
        $seller = $this->newQuery()->find($id);
        $seller->update($data);
        return $seller;
    }

    public function delete(string $id)
    {
        return $this->newQuery()->find($id)->delete();
    }

    public function paginate(int $take = 15, int $quantity = 50)
    {
        return $this->getAll(true, $take, $quantity);
    }

    public function createTokenById(string $id, $tokenName = 'api_token')
    {
        return $this->newQuery()->find($id)->createToken($tokenName)->plainTextToken;
    }
}
