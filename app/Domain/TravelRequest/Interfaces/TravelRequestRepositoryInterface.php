<?php
namespace App\Domain\TravelRequest\Interfaces;

interface TravelRequestRepositoryInterface
{
    public function filter(array $filters, $currentUser): \Illuminate\Support\Collection;
}
