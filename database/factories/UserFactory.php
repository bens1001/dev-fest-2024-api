<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    protected $model = User::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'gender' => fake()->randomElement(['male', 'female']),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => fake()->regexify('[A-Za-z0-9]{20}'),
        ];
    }

    /**
     * Create a user and assign a random role.
     *
     * @param array $roles
     * @return User
     */
    public function createWithRandomRole(array $roles = ['admin', 'manager', 'operator'])
    {
        // Create the user
        $user = $this->create();

        // Assign a random role to the user after creation
        $user->assignRole(fake()->randomElement($roles));

        return $user;
    }

}
