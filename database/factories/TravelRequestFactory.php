<?php

namespace Database\Factories;

use App\Domain\TravelRequest\Enums\TravelStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TravelRequest>
 */
class TravelRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departure = $this->faker->dateTimeBetween('+1 days', '+1 month');
        $return = (clone $departure)->modify('+'.rand(2, 10).' days');

        return [
            'destination' => $this->faker->city,
            'departure_date' => $departure,
            'return_date' => $return,
            'status' => $this->faker->randomElement(TravelStatus::values()),
        ];
    }
}
