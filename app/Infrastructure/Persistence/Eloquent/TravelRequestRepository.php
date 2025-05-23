<?php
namespace App\Infrastructure\Persistence\Eloquent;

use App\Models\TravelRequest;
use App\Domain\TravelRequest\Interfaces\TravelRequestRepositoryInterface;

class TravelRequestRepository implements TravelRequestRepositoryInterface
{
    public function filter(array $filters, $currentUser): \Illuminate\Support\Collection
    {
        $query = TravelRequest::query();

        if (!empty($filters['destination'])) {
            $query->where('destination', 'like', '%' . $filters['destination'] . '%');
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['departure_date'])) {
            $query->whereDate('departure_date', '=', $filters['departure_date']);
        }

        if (!empty($filters['return_date'])) {
            $query->whereDate('return_date', '=', $filters['return_date']);
        }

        if ($currentUser->hasRole('user')) {
            $query->where('user_id', $currentUser->id);
        }

        return $query->orderByDesc('id')->get();
    }

    public function create(array $data): TravelRequest
    {
        return TravelRequest::create($data);
    }

    public function update(string $id, array $data): TravelRequest
    {
        $travelRequest = TravelRequest::findOrFail($id);
        $travelRequest->update($data);
        return $travelRequest;
    }

    public function find(string $id): ?TravelRequest
    {
        return TravelRequest::find($id);
    }
}
