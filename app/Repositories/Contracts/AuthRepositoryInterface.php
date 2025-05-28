<?php

namespace App\Repositories\Contracts;

interface AuthRepositoryInterface
{
    public function createTokenById(string $id, string $tokenName = 'api_token');
}