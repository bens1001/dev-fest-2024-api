<?php

namespace Database\Factories;

use App\Models\SensorReading;
use App\Models\Machine;
use Illuminate\Database\Eloquent\Factories\Factory;

class SensorReadingFactory extends Factory
{
    protected $model = SensorReading::class;

    public function definition()
    {
        // Select a random machine from the database
        $machine = Machine::inRandomOrder()->first();

        // Generate sensor data based on the machine type
        $sensorData = $this->generateSensorData($machine);

        return [
            'machine_id' => $machine->id,
            'sensor_data' => json_encode($sensorData),
            'reading_time' => $this->faker->dateTime(),
        ];
    }

    protected $casts = [
        'sensor_data' => 'json',
    ];

    /**
     * Generate sensor data based on the machine type.
     *
     * @param Machine $machine
     * @return array
     */
    private function generateSensorData(Machine $machine): array
    {
        switch ($machine->machine_type) {
            case 'Welding Robot':
                return [
                    'machine_id' => $machine->id,
                    'weld_temperature' => $this->faker->numberBetween(1500, 1700),
                    'weld_current' => $this->faker->numberBetween(100, 200),
                    'weld_voltage' => $this->faker->numberBetween(20, 40),
                    'weld_time' => $this->faker->numberBetween(400, 600),
                    'pressure_applied' => $this->faker->numberBetween(800, 1200),
                    'arm_position' => [
                        'x' => $this->faker->randomFloat(2, 100, 150),
                        'y' => $this->faker->randomFloat(2, 70, 90),
                        'z' => $this->faker->randomFloat(2, 180, 220),
                    ],
                    'wire_feed_rate' => $this->faker->numberBetween(1, 10),
                    'gas_flow_rate' => $this->faker->numberBetween(15, 25),
                    'weld_strength_estimate' => $this->faker->numberBetween(1800, 2200),
                    'vibration_level' => $this->faker->randomFloat(2, 0, 1),
                    'power_consumption' => $this->faker->randomFloat(2, 2, 5),
                    'timestamp' => $this->faker->iso8601(),
                ];

            case 'Stamping Press':
                return [
                    'machine_id' => $machine->id,
                    'force_applied' => $this->faker->numberBetween(400, 600),
                    'cycle_time' => $this->faker->numberBetween(10, 15),
                    'temperature' => $this->faker->numberBetween(60, 80),
                    'vibration_level' => $this->faker->randomFloat(2, 0.5, 1.5),
                    'cycle_count' => $this->faker->numberBetween(1400, 1600),
                    'oil_pressure' => $this->faker->randomFloat(1, 2, 5),
                    'die_alignment' => $this->faker->randomElement(['aligned', 'misaligned']),
                    'sheet_thickness' => $this->faker->randomFloat(1, 1, 3),
                    'power_consumption' => $this->faker->randomFloat(2, 10, 15),
                    'noise_level' => $this->faker->numberBetween(80, 90),
                    'lubrication_flow_rate' => $this->faker->randomFloat(2, 0.5, 1),
                    'timestamp' => $this->faker->iso8601(),
                ];

            case 'Painting Robot':
                return [
                    'machine_id' => $machine->id,
                    'spray_pressure' => $this->faker->randomFloat(1, 2, 5),
                    'paint_thickness' => $this->faker->numberBetween(100, 150),
                    'arm_position' => [
                        'x' => $this->faker->randomFloat(2, 100, 150),
                        'y' => $this->faker->randomFloat(2, 70, 90),
                        'z' => $this->faker->randomFloat(2, 180, 220),
                    ],
                    'temperature' => $this->faker->numberBetween(20, 30),
                    'humidity' => $this->faker->numberBetween(50, 70),
                    'paint_flow_rate' => $this->faker->randomFloat(2, 3, 5),
                    'paint_volume_used' => $this->faker->randomFloat(2, 0.5, 1),
                    'atomizer_speed' => $this->faker->numberBetween(15000, 25000),
                    'overspray_capture_efficiency' => $this->faker->numberBetween(90, 100),
                    'booth_airflow_velocity' => $this->faker->randomFloat(2, 0.2, 1),
                    'solvent_concentration' => $this->faker->randomFloat(2, 5, 10),
                    'timestamp' => $this->faker->iso8601(),
                ];

            case 'Automated Guided Vehicle':
                return [
                    'machine_id' => $machine->id,
                    'location' => [
                        'x' => $this->faker->randomFloat(2, 140, 160),
                        'y' => $this->faker->randomFloat(2, 80, 100),
                        'z' => $this->faker->numberBetween(0, 10),
                    ],
                    'battery_level' => $this->faker->numberBetween(70, 100),
                    'load_weight' => $this->faker->numberBetween(300, 700),
                    'speed' => $this->faker->randomFloat(2, 0.5, 2),
                    'distance_traveled' => $this->faker->numberBetween(1000, 1500),
                    'obstacle_detection' => $this->faker->randomElement(['yes', 'no']),
                    'navigation_status' => $this->faker->randomElement(['en_route', 'waiting', 'rerouting']),
                    'vibration_level' => $this->faker->randomFloat(2, 0, 1),
                    'temperature' => $this->faker->numberBetween(30, 40),
                    'wheel_rotation_speed' => $this->faker->numberBetween(300, 500),
                    'timestamp' => $this->faker->iso8601(),
                ];

            case 'CNC Machine':
                return [
                    'machine_id' => $machine->id,
                    'spindle_speed' => $this->faker->numberBetween(10000, 15000),
                    'tool_wear_level' => $this->faker->numberBetween(0, 100),
                    'cut_depth' => $this->faker->numberBetween(1, 10),
                    'feed_rate' => $this->faker->numberBetween(200, 400),
                    'vibration_level' => $this->faker->randomFloat(2, 0, 1.5),
                    'coolant_flow_rate' => $this->faker->randomFloat(2, 0, 1),
                    'material_hardness' => $this->faker->numberBetween(100, 300),
                    'power_consumption' => $this->faker->randomFloat(2, 5, 15),
                    'temperature' => $this->faker->numberBetween(50, 60),
                    'chip_load' => $this->faker->randomFloat(2, 0, 1),
                    'timestamp' => $this->faker->iso8601(),
                ];

            case 'Leak Test Machine':
                return [
                    'machine_id' => $machine->id,
                    'test_pressure' => $this->faker->randomFloat(1, 1, 10),
                    'pressure_drop' => $this->faker->randomFloat(2, 0, 1),
                    'leak_rate' => $this->faker->randomFloat(2, 0, 5),
                    'test_duration' => $this->faker->numberBetween(30, 60),
                    'temperature' => $this->faker->numberBetween(20, 30),
                    'status' => $this->faker->randomElement(['pass', 'fail']),
                    'fluid_type' => $this->faker->randomElement(['air', 'water']),
                    'seal_condition' => $this->faker->randomElement(['good', 'warning', 'fail']),
                    'test_cycle_count' => $this->faker->numberBetween(1000, 2000),
                    'timestamp' => $this->faker->iso8601(),
                ];

            default:
                return [];
        }
    }
}
