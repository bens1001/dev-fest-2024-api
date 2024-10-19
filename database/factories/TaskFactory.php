<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->randomElement(User::pluck('id')),
            'task_description' => fake()->randomElement(['maintenance', 'battery recharge','production logging','energy usage logging']),
            'due_date' => fake()->dateTimeBetween('now', '+1 month'),
            'status' => fake()->randomElement(['todo', 'pending', 'completed']),
        ];
    }
}
