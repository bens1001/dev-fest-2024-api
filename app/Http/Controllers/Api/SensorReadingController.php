<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorReading;
use App\Http\Resources\SensorReadingResource;
use Illuminate\Support\Facades\DB;

/**
 * @group Sensor Reading
 *
 * APIs for Sensor Reading management.
 */
class SensorReadingController extends Controller
{
    /**
     * List Sensor Readings
     *
     * Retrieve a paginated list of sensor readings, with optional filtering by sensor ID.
     *
     * Before using this endpoint:
     * - Ensure sensor readings are available.
     *
     * After using this endpoint:
     * - You will receive a paginated list of sensor readings, optionally filtered by sensor ID.
     *
     * @apiResourceCollection App\Http\Resources\SensorReadingResource
     * @apiResourceModel App\Models\SensorReading paginate=10
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "sensor_id": 3,
     *       "reading_value": 45.6,
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
            if ($request->has('sensor_id')) {
                $query->where('sensor_id', $request->sensor_id);
            }

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
     *   "sensor_id": 3,
     *   "reading_value": 45.6,
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
