<?php

namespace Database\Seeders;

use App\Models\Alert;
use App\Models\Defect;
use App\Models\EnergyUsage;
use App\Models\Machine;
use App\Models\Production;
use App\Models\SensorReading;
use App\Models\TaskUser;
use App\Models\User;
use App\Models\Task;
use App\Models\UserAlert;
use Illuminate\Database\Seeder;
use Database\Seeders\RolesAndPermissionsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(RolesAndPermissionsSeeder::class);

        // User::factory()->count(10)->createWithRandomRole();
        $users = User::factory()->count(10)->create()->each(function ($user) {
            $user->assignRole(fake()->randomElement(['admin', 'manager', 'operator']));
        });

        Machine::factory()->count(10)->create();
        EnergyUsage::factory()->count(10)->create();
        SensorReading::factory()->count(10)->create();
        Production::factory()->count(10)->create();
        $tasks = Task::factory()->count(10)->create();
        $alerts = Alert::factory()->count(10)->create();
        Defect::factory()->count(10)->create();

        foreach ($users as $user) {
            $randomTasks = $tasks->random(rand(1, 3)); // Randomly assign 1 to 3 tasks
            $user->tasks()->attach($randomTasks); // Assuming you have a `tasks` relationship defined in User model
        }

        foreach ($users as $user) {
            $randomAlerts = $alerts->random(rand(1, 3)); // Randomly assign 1 to 3 alerts
            $user->alerts()->attach($randomAlerts); // Assuming you have a `alerts` relationship defined in User model
        }
    }
}
