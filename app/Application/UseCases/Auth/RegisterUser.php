<?php

namespace App\Application\UseCases\Auth;

use App\Domain\TravelRequest\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class RegisterUser
{
    public function __construct(protected UserRepositoryInterface $users){}

    public function handle(array $data)
    {
        return $this->users->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' =>  Hash::make($data['password']),
        ]);
    }
}
