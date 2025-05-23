<?php

namespace App\Http\Controllers\Travels;

use App\Application\UseCases\TravelRequest\ListTravelRequests;
use App\Notifications\TravelRequestStatusNotification;
use Illuminate\Validation\Rules\Enum;
use App\Domain\TravelRequest\Enums\TravelStatus;
use App\Http\Controllers\Controller;
use App\Models\TravelRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TravelRequestController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/travel-requests",
     *     summary="Listar pedidos de viagem",
     *     tags={"Pedidos de Viagem"},
     *     @OA\Parameter(name="destination", in="query", required=false, description="Filtrar por destino", @OA\Schema(type="string")),
     *     @OA\Parameter(name="user_id", in="query", required=false, description="Filtrar por ID do usuário", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="departure_date", in="query", required=false, description="Filtrar por data de ida (YYYY-MM-DD)", @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="return_date", in="query", required=false, description="Filtrar por data de volta (YYYY-MM-DD)", @OA\Schema(type="string", format="date")),
     *     @OA\Response(response=200, description="Lista de pedidos de viagem")
     * )
     */
    public function index(Request $request, ListTravelRequests $useCase)
    {
        $filters = $request->only([
            'destination', 'user_id', 'departure_date', 'return_date'
        ]);

        $requests = $useCase->handle($filters, auth()->user());

        return response()->json([
            'data' => $requests
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/api/travel-requests",
     *     summary="Criar um novo pedido de viagem",
     *     tags={"Pedidos de Viagem"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"destination", "departure_date", "return_date", "status", "user_id"},
     *             @OA\Property(property="destination", type="string", example="São Paulo"),
     *             @OA\Property(property="departure_date", type="string", format="date", example="2025-06-01"),
     *             @OA\Property(property="return_date", type="string", format="date", example="2025-06-10"),
     *             @OA\Property(property="status", type="string", example="solicitado"),
     *             @OA\Property(property="user_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Pedido criado com sucesso"),
     *     @OA\Response(response=422, description="Erro de validação")
     * )
     */
    public function store(Request $request)
    {

        $data = $request->only('destination', 'departure_date', 'return_date', 'status', 'user_id');

        $validated = Validator::make($data, [
            'destination' => 'required',
            'departure_date' => 'date',
            'return_date' => 'date',
            'status' => ['required', new Enum(TravelStatus::class)],
            'user_id' => 'exists:users,id'
        ]);

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }

        if (!auth()->user()->hasRole('admin')) {
            $data['status'] = TravelStatus::SOLICITADO->value;
        }

        TravelRequest::create([
            'destination' => $data['destination'],
            'departure_date' => $data['departure_date'],
            'return_date' => $data['return_date'],
            'status' => $data['status'],
            'user_id' => $data['user_id']
        ]);

        return response()->json(['message' => 'Request submitted successfully.'], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/travel-requests/{id}",
     *     summary="Visualizar um pedido de viagem",
     *     tags={"Pedidos de Viagem"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do pedido de viagem",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Dados do pedido")
     * )
     */
    public function show(string $id)
    {
        //
        $data = TravelRequest::find($id);

        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/api/travel-requests/{id}",
     *     summary="Atualizar todos os dados do pedido de viagem",
     *     tags={"Pedidos de Viagem"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"destination", "departure_date", "return_date", "status", "user_id"},
     *             @OA\Property(property="destination", type="string", example="Salvador"),
     *             @OA\Property(property="departure_date", type="string", format="date", example="2025-07-01"),
     *             @OA\Property(property="return_date", type="string", format="date", example="2025-07-10"),
     *             @OA\Property(property="status", type="string", example="aprovado"),
     *             @OA\Property(property="user_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Pedido atualizado com sucesso"),
     *     @OA\Response(response=422, description="Erro de validação"),
     *     @OA\Response(response=403, description="Acesso negado (usuário comum não pode alterar status)")
     * )
     *
     * @OA\Patch(
     * path="/api/travel-requests/{id}",
     * summary="Atualizar apenas o status do pedido (apenas admin)",
     * tags={"Pedidos de Viagem"},
     * @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"status"},
     * @OA\Property(property="status", type="string", example="cancelado")
     * )
     * ),
     * @OA\Response(response=200, description="Status atualizado com sucesso"),
     * @OA\Response(response=422, description="Erro de validação"),
     * @OA\Response(response=403, description="Acesso negado (apenas admin)")
     * )
     */
    public function update(Request $request, string $id)
    {
        $data = $request->only('destination', 'departure_date', 'return_date', 'status', 'user_id');

        $travel = TravelRequest::findOrFail($id);

        if (auth()->user()->hasRole('user') && $travel->user_id !== auth()->id()) {
            return response()->json(['error' => 'Você não tem permissão para atualizar este pedido.'], 403);
        }

        if ($request->isMethod('put')) {
            $rules = [
                'destination' => 'required|string',
                'departure_date' => 'required|date',
                'return_date' => 'required|date',
                'user_id' => 'required|exists:users,id',
            ];

            if (auth()->user()->hasRole('admin')) {
                $rules['status'] = ['required', new Enum(TravelStatus::class)];
            }

            $validated = Validator::make($data, $rules);

            if ($validated->fails()) {
                return response()->json(['errors' => $validated->errors()], 422);
            }

            if (!auth()->user()->hasRole('admin')) {
                unset($data['status']);
            }

            $travel->update($data);

        } elseif ($request->isMethod('patch')) {
            if (!auth()->user()->hasRole('admin')) {
                return response()->json(['error' => 'Somente administradores podem alterar o status.'], 403);
            }

            $validated = Validator::make($data, [
                'status' => ['required', new Enum(TravelStatus::class)],
            ]);

            if ($validated->fails()) {
                return response()->json(['errors' => $validated->errors()], 422);
            }

            $travel->update([
                'status' => $data['status'],
            ]);

            if (in_array($data['status'], [TravelStatus::APROVADO->value, TravelStatus::CANCELADO->value])) {
                $travel->user->notify(new TravelRequestStatusNotification($travel));
            }
        }

        return response()->json([
            'message' => 'Request updated successfully.',
            'data' => $travel
        ], 200);
    }


    /**
     * @OA\Delete(
     *     path="/api/travel-requests/{id}",
     *     summary="Excluir um pedido de viagem",
     *     tags={"Pedidos de Viagem"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Pedido excluído com sucesso"),
     *     @OA\Response(response=403, description="Acesso negado (somente dono do pedido ou admin)")
     * )
     */
    public function destroy(string $id)
    {
        $travel = TravelRequest::findOrFail($id);

        if (auth()->user()->hasRole('user') && $travel->user_id !== auth()->id()) {
            return response()->json(['error' => 'Você não tem permissão para excluir este pedido.'], 403);
        }

        $travel->delete();

        return response()->json(['message' => 'Request deleted successfully.'], 200);
    }
}
