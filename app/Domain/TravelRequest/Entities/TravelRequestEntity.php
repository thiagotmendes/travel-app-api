<?php
namespace App\Domain\TravelRequest\Entities;

use App\Domain\TravelRequest\Enums\TravelStatus;

class TravelRequestEntity
{
    public function __construct(
        public readonly string $destination,
        public readonly \DateTime $departureDate,
        public readonly \DateTime $returnDate,
        public readonly string $status,
        public readonly int $userId
    ) {}

    public function canBeCancelled(): bool
    {
        return $this->status === TravelStatus::APROVADO->value;
    }

    public function isValidStatus(): bool
    {
        return in_array($this->status, TravelStatus::values());
    }
}
