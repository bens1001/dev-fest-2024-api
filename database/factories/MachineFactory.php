<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Machine;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Machine>
 */
class MachineFactory extends Factory
{

    protected $model = Machine::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'machine_type' => fake()->randomElement(['Welding Robot', 'Stamping Press', 'Painting Robot', 'Automated Guided Vehicle', 'CNC Machine', 'Leak Test Machine']),
            'machine_name' => fake()->word(),
            'status' => fake()->randomElement(['running', 'idle', 'maintenance']),
            'last_maintenance' => fake()->dateTimeBetween('-1 year', 'now'),
            'first_usage' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
