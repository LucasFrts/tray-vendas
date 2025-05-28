<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Support\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected string $modelClass = User::class;

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
        $user =  $this->find($id);
        $user->update($data);
        return $user;
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
