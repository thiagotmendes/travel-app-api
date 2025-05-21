<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\TravelRequest\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data)
    {
        // TODO: Implement create() method.
        return User::create($data);
    }
}
