<?php
namespace App\Domain\TravelRequest\Interfaces;

use App\Models\TravelRequest;

interface TravelRequestRepositoryInterface
{
    public function filter(array $filters, $currentUser): \Illuminate\Support\Collection;

    public function create(array $data): TravelRequest;
    public function update(string $id, array $data): \App\Models\TravelRequest;
    public function find(string $id): ?TravelRequest;
}
