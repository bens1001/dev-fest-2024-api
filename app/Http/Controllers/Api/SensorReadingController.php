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

    public function show(int $sensor_reading_id)
    {
        try {
            $sensorReading = SensorReading::findOrFail($sensor_reading_id);
            return new SensorReadingResource($sensorReading);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    public function destroy(int $sensor_reading_id)
    {
        try {
            DB::beginTransaction();

            $sensorReading = SensorReading::findOrFail($sensor_reading_id);
            $sensorReading->delete();

            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }
}
