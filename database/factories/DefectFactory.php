<?php

namespace Database\Factories;

use App\Models\Defect;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Machine;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Defect>
 */
class DefectFactory extends Factory
{
    protected $model = Defect::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'machine_id' => fake()->randomElement(Machine::pluck('id')),
            'defect_type' => fake()->randomElement(['mechanical', 'electrical', 'software']),
            'defect_time' => fake()->dateTimeBetween('-1 week', 'now'),
        ];
    }
}
