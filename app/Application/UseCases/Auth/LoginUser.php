<?php

namespace App\Application\UseCases\Auth;

use Tymon\JWTAuth\Facades\JWTAuth;

class LoginUser
{
    public function handle(array $credentials)
    {
        if(!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $token;
    }
}
