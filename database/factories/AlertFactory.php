<?php

namespace Database\Factories;

use App\Models\Alert;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Machine;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alert>
 */
class AlertFactory extends Factory
{
    protected $model = Alert::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'machine_id' => fake()->randomElement(Machine::pluck('machine_id')),
            'alert_message' => fake()->sentence(),
            'alert_time' => fake()->dateTime(),
        ];
    }
}
