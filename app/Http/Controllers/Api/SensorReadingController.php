<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorReading;
use App\Http\Resources\SensorReadingResource;
use Illuminate\Support\Facades\DB;
use App\Events\SensorReadingBroadcasted;
use App\Models\Machine;

/**
 * @group Sensor Reading
 *
 * APIs for Sensor Reading management.
 */
class SensorReadingController extends Controller
{
    /**
     * Store sensor readings received from a webhook.
     *
     * This endpoint receives sensor data from a specified machine and stores it in the database.
     *
     * @unauthenticated
     * 
     * @response 201 {
     *   "message": "Sensor readings saved successfully"
     * }
     * @response 400 {
     *   "error": "Failed to save sensor readings: <error_message>"
     * }
     * @response 500 {
     *   "error": "Failed to save sensor readings: <error_message>"
     * }
     */
    public function receiveData(Request $request)
    {
        DB::beginTransaction(); // Start the transaction

        try {
            $data = $request->json()->all(); // Get the JSON data from the request

            // Extract the machine_id from the received data
            $machineIdFromData = $data['machine_id'];

            // Define a mapping of machine IDs to their corresponding types
            $machineTypeMapping = [
                'stamping_press_001' => 'Stamping Press',
                'welding_robot_006' => 'Welding Robot',
                'painting_robot_002' => 'Painting Robot',
                'agv_003' => 'Automated Guided Vehicle',
                'cnc_milling_004' => 'CNC Machine',
                'leak_test_005' => 'Leak Test Machine',
            ];

            // Get the machine type based on the machine_id from the data
            $machineType = $machineTypeMapping[$machineIdFromData];

            // Fetch the corresponding machine ID from the database
            $machineIdFromTable = Machine::where('machine_type', $machineType)->value('id');

            // Create the sensor reading
            $sensorReading = SensorReading::create([
                'machine_id' => $machineIdFromTable,
                'sensor_data' => json_encode($data),
                'reading_time' => now(),
            ]);

            broadcast(new SensorReadingBroadcasted($sensorReading))->toOthers();

            DB::commit();
            return response()->json(['message' => 'Sensor readings saved successfully'], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Failed to save sensor readings: ' . $e->getMessage()], 500);
        }
    }

    /**
     * List Sensor Readings
     *
     * Retrieve a paginated list of sensor readings, with optional filtering by machine ID.
     *
     * Before using this endpoint:
     * - Ensure sensor readings are available.
     *
     * After using this endpoint:
     * - You will receive a paginated list of sensor readings, optionally filtered by machine ID.
     *
     * @apiResourceCollection App\Http\Resources\SensorReadingResource
     * @apiResourceModel App\Models\SensorReading paginate=10
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "machine_id": 2,
     *       "sensor_data": " {
     *          "machine_id": "welding_robot_006",
     *          "weld_temperature": 1600,             // in °C
     *          "weld_current": 150,                  // in A (amperes)
     *          "weld_voltage": 30,                   // in V
     *          "weld_time": 500,                     // in milliseconds
     *          "pressure_applied": 1000,             // in N (newtons)
     *          "arm_position": {"x": 120.5, "y": 80.4, "z": 200.3},  // spatial coordinates
     *          "wire_feed_rate": 5,                  // in mm/min (arc welding)
     *          "gas_flow_rate": 20,                  // in l/min
     *          "weld_strength_estimate": 2000,       // in N
     *          "vibration_level": 0.2,               // in mm/s
     *          "power_consumption": 3.5,             // in kWh
     *          "timestamp": "2024-10-14T11:00:00Z"
     *       }",
     *       "reading_time": "2024-10-18T10:00:00.000000Z",
     *       "created_at": "2024-10-18T10:00:00.000000Z",
     *       "updated_at": "2024-10-18T10:00:00.000000Z"
     *     },
     *     ...
     *   ],
     *   "links": {...},
     *   "meta": {...}
     * }
     * @response 404 {"message": "Not found"}
     */
    public function index(Request $request)
    {
        try {
            $query = SensorReading::query();

            // Optional filtering
            if ($request->has('machine_id')) {
                $query->where('machine_id', $request->machine_id);
            }

            $query->orderBy('reading_time', 'desc');

            // You can add more filters here as needed
            $sensorReadings = $query->paginate($request->per_page ?? 10);

            return SensorReadingResource::collection($sensorReadings);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Show a Sensor Reading
     *
     * Retrieve details of a specific sensor reading by ID.
     *
     * Before using this endpoint:
     * - Ensure that the sensor reading exists.
     *
     * After using this endpoint:
     * - You will receive the details of the selected sensor reading.
     *
     * @apiResource App\Http\Resources\SensorReadingResource
     * @apiResourceModel App\Models\SensorReading
     *
     * @response 200 {
     *   "id": 1,
     *   "machine_id": 2,
     *   "sensor_data": " {
     *          "machine_id": "welding_robot_006",
     *          "weld_temperature": 1600,             // in °C
     *          "weld_current": 150,                  // in A (amperes)
     *          "weld_voltage": 30,                   // in V
     *          "weld_time": 500,                     // in milliseconds
     *          "pressure_applied": 1000,             // in N (newtons)
     *          "arm_position": {"x": 120.5, "y": 80.4, "z": 200.3},  // spatial coordinates
     *          "wire_feed_rate": 5,                  // in mm/min (arc welding)
     *          "gas_flow_rate": 20,                  // in l/min
     *          "weld_strength_estimate": 2000,       // in N
     *          "vibration_level": 0.2,               // in mm/s
     *          "power_consumption": 3.5,             // in kWh
     *          "timestamp": "2024-10-14T11:00:00Z"
     *   }",
     *   "reading_time": "2024-10-18T10:00:00.000000Z",
     *   "created_at": "2024-10-18T10:00:00.000000Z",
     *   "updated_at": "2024-10-18T10:00:00.000000Z"
     * }
     * @response 404 {"message": "Not found"}
     */
    public function show(int $sensor_reading_id)
    {
        try {
            $sensorReading = SensorReading::findOrFail($sensor_reading_id);
            return new SensorReadingResource($sensorReading);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Delete a Sensor Reading
     *
     * Permanently remove a sensor reading from the system.
     *
     * Before using this endpoint:
     * - Ensure that the sensor reading exists.
     *
     * After using this endpoint:
     * - The sensor reading will be deleted from the system.
     *
     * @response 200 {"message": "Sensor reading deleted"}
     * @response 404 {"message": "Not found"}
     */
    public function destroy(int $sensor_reading_id)
    {
        try {
            DB::beginTransaction();

            $sensorReading = SensorReading::findOrFail($sensor_reading_id);
            $sensorReading->delete();

            DB::commit();
            return response()->json(['message' => 'Sensor Reading deleted'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }
}
