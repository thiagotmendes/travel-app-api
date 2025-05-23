<?php
namespace App\Application\UseCases\TravelRequest;

use App\Domain\TravelRequest\Interfaces\TravelRequestRepositoryInterface;

class ListTravelRequests
{
    public function __construct(
        protected TravelRequestRepositoryInterface $repository
    ) {}

    public function handle(array $filters, $currentUser): \Illuminate\Support\Collection
    {
        return $this->repository->filter($filters, $currentUser);
    }
}
