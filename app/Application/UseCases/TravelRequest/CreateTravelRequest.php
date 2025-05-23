<?php
namespace App\Application\UseCases\TravelRequest;

use App\Domain\TravelRequest\Entities\TravelRequestEntity;
use App\Domain\TravelRequest\Enums\TravelStatus;
use Illuminate\Validation\Rules\Enum as EnumRule;
use Illuminate\Validation\ValidationException;
use App\Domain\TravelRequest\Interfaces\TravelRequestRepositoryInterface;
use Illuminate\Support\Facades\Validator;

class CreateTravelRequest
{
    public function __construct(
        protected TravelRequestRepositoryInterface $repository
    ) {}

    public function handle(array $data, $user): void
    {
        $validator = Validator::make($data, [
            'destination' => 'required|string',
            'departure_date' => 'required|date',
            'return_date' => 'required|date',
            'status' => ['required', new EnumRule(TravelStatus::class)],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        if (!$user->hasRole('admin')) {
            $data['status'] = TravelStatus::SOLICITADO->value;
        }

        $entity = new TravelRequestEntity(
            destination: $data['destination'],
            departureDate: new \DateTime($data['departure_date']),
            returnDate: new \DateTime($data['return_date']),
            status: $data['status'],
            userId: $user->id
        );

        if (!$entity->isValidStatus()) {
            throw ValidationException::withMessages(['status' => 'Status inválido.']);
        }

        $this->repository->create([
            'destination' => $entity->destination,
            'departure_date' => $entity->departureDate->format('Y-m-d'),
            'return_date' => $entity->returnDate->format('Y-m-d'),
            'status' => $entity->status,
            'user_id' => $entity->userId,
        ]);
    }
}
