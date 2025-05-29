<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Seller;

class SellerPolicy
{
    public function create($actor)
    {
        // apenas User (admin) cria sellers
        return $actor instanceof User;
    }
    public function update($actor, Seller $seller)
    {
        return $actor instanceof User;
    }
    public function delete($actor, Seller $seller)
    {
        return $actor instanceof User;
    }
    public function viewAny($actor)
    {
        // todo autenticado (User ou Seller) pode listar
        return $actor instanceof User || $actor instanceof Seller;
    }
    public function view($actor, Seller $seller)
    {
        return $actor instanceof User || $actor instanceof Seller;
    }
}
