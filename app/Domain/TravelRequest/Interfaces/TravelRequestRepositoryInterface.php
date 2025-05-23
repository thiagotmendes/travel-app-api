<?php
namespace App\Domain\TravelRequest\Interfaces;

interface TravelRequestRepositoryInterface
{
    public function filter(array $filters, $currentUser): \Illuminate\Support\Collection;

    public function create(array $data): \App\Models\TravelRequest;
}
