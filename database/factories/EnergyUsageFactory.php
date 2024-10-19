<?php

namespace Database\Factories;

use App\Models\EnergyUsage;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Machine;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EnergyUsage>
 */
class EnergyUsageFactory extends Factory
{
    protected $model = EnergyUsage::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'machine_id' => fake()->randomElement(Machine::pluck('id')),
            'energy_consumed' => fake()->randomFloat(2, 0, 1000),
            'start_shift_time' => fake()->dateTime(),
            'end_shift_time' => fake()->dateTime(),
        ];
    }
}
