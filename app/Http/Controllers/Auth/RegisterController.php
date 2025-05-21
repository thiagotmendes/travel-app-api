<?php

namespace App\Http\Controllers\Auth;

use App\Application\UseCases\Auth\RegisterUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registro de novo usuário",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", example="Thiago Mendes"),
     *             @OA\Property(property="email", type="string", format="email", example="thiago@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Thiago Mendes"),
     *                 @OA\Property(property="email", type="string", example="thiago@example.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     )
     * )
     */
    public function register(Request $request, RegisterUser $useCase): \Illuminate\Http\JsonResponse
    {
        $data = $request->only(['name', 'email', 'password', 'password_confirmation']);

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if( $validator->fails() ) {
            return response()->json($validator->errors(), 422);
        }

        $users = $useCase->handle($data);

        return response()->json([$users], 201);
    }
}
