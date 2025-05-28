<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Hashing\Hasher;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private Hasher $hasher
    ) {}

    public function getAll(bool $paginate = false, int $take  = 15, int $quantity = 50)
    {
        if ($paginate) {
            return $this->userRepository->paginate($take, $quantity);
        }
        return $this->userRepository->all();
    }

    public function find(string $id)
    {
        return $this->userRepository->find($id);
    }
    public function create(array $data)
    {
        return $this->userRepository->create(
            array_merge($data, ['password' => $this->hasher->make($data['password'])])
        );
    }
    public function update(string $id, array $data)
    {
        return $this->userRepository->update($id, $data);
    }
    public function delete(string $id)
    {
        return $this->userRepository->delete($id);
    }

    public function createTokenById(string $id, $tokenName = 'api_token') : string{
        return $this->userRepository->createTokenById($id, $tokenName);
    }

}
