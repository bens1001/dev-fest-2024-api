<?php

namespace Database\Factories;

use App\Models\Machine;
use App\Models\Production;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Production>
 */
class ProductionFactory extends Factory
{
    protected $model = Production::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'machine_id' => fake()->randomElement(Machine::pluck('machine_id')),
            'start_time' => fake()->dateTimeBetween('-1 week', 'now'),
            'end_time' => fake()->dateTimeBetween('now', '+1 week'),
            'output_quantity' => fake()->numberBetween(1, 1000),
            'defects_quantity' => fake()->numberBetween(0, 100),
        ];
    }
}
