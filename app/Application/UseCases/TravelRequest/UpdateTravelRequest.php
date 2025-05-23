<?php


namespace App\Application\UseCases\TravelRequest;

use App\Domain\TravelRequest\Enums\TravelStatus;
use App\Domain\TravelRequest\Entities\TravelRequestEntity;
use App\Domain\TravelRequest\Interfaces\TravelRequestRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum as EnumRule;
use Illuminate\Validation\ValidationException;

class UpdateTravelRequest
{
    public function __construct(
        protected TravelRequestRepositoryInterface $repository
    )
    {
    }

    public function handle(string $id, array $data, $user, string $method): array
    {
        return match (strtoupper($method)) {
            'PUT' => $this->handlePut($id, $data, $user),
            'PATCH' => $this->handlePatch($id, $data, $user),
            default => throw new \RuntimeException("Método não suportado")
        };
    }

    private function handlePut(string $id, array $data, $user): array
    {
        $travel = $this->repository->find($id);

        if (!$travel) {
            throw ValidationException::withMessages(['id' => 'Pedido de viagem não encontrado']);
        }

        if ($user->hasRole('user') && $travel->user_id !== $user->id) {
            throw ValidationException::withMessages([
                'permission' => 'Você não tem permissão para editar este pedido.'
            ]);
        }

        $rules = [
            'destination' => 'required|string',
            'departure_date' => 'required|date',
            'return_date' => 'required|date',
        ];

        if ($user->hasRole('admin')) {
            $rules['status'] = ['required', new EnumRule(TravelStatus::class)];
        } else {
            // Usuário comum não pode enviar o campo status
            if (isset($data['status'])) {
                throw ValidationException::withMessages([
                    'permission' => 'Você não pode definir o status deste pedido.'
                ]);
            }
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Se usuário comum estiver editando uma viagem aprovada, status volta para solicitado
        if ($user->hasRole('user') && $travel->status === TravelStatus::APROVADO) {
            $data['status'] = TravelStatus::SOLICITADO->value;
        } elseif ($user->hasRole('user')) {
            $data['status'] = $travel->status->value;
        }

        $entity = new TravelRequestEntity(
            destination: $data['destination'],
            departureDate: new \DateTime($data['departure_date']),
            returnDate: new \DateTime($data['return_date']),
            status: $data['status'],
            userId: $travel->user_id
        );

        $inputDataArray = [
            'destination' => $entity->destination,
            'departure_date' => $entity->departureDate->format('Y-m-d'),
            'return_date' => $entity->returnDate->format('Y-m-d'),
            'status' => $entity->status,
        ];

        $updated = $this->repository->update($id, $inputDataArray);

        return ['data' => $updated];
    }

    private function handlePatch(string $id, array $data, $user): array
    {
        if (!$user->hasRole('admin')) {
            throw ValidationException::withMessages(['permission' => 'Somente administradores podem alterar o status.']);
        }

        $travel = $this->repository->find($id);

        if (!$travel) {
            throw ValidationException::withMessages(['id' => 'Pedido de viagem não encontrado']);
        }

        $validator = Validator::make($data, [
            'status' => ['required', new EnumRule(TravelStatus::class)],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $inputDataArray = [
            'status' => $data['status'],
        ];

        $updated = $this->repository->update($id, $inputDataArray);

        return ['data' => $updated, 'notify' => true];
    }
}
