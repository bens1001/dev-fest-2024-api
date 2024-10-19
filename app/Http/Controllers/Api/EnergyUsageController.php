<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnergyUsage\StoreEnergyUsageRequest;
use App\Http\Requests\EnergyUsage\UpdateEnergyUsageRequest;
use Illuminate\Support\Facades\DB;
use App\Models\EnergyUsage;
use App\Models\Machine;
use Illuminate\Http\Request;

/**
 * @group Energy Usage
 *
 * APIs for Energy Usage management.
 */
class EnergyUsageController extends Controller
{
    /**
     * List Energy Usage Records
     *
     * Retrieve a paginated list of energy usage records.
     *
     * Before using this endpoint:
     * - Ensure energy usage records have been added to the system.
     *
     * After using this endpoint:
     * - You will get a list of energy usage records, filtered as needed.
     *
     * @apiResourceCollection App\Http\Resources\EnergyUsageResource
     * @apiResourceModel App\Models\EnergyUsage paginate=10
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "machine_id": 1,
     *       "energy_consumed": 123.45,
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
            $energyUsage = EnergyUsage::paginate($request->per_page ?? 10);

            return response()->json($energyUsage, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Show an Energy Usage Record
     *
     * Retrieve details of a specific energy usage record by ID.
     *
     * Before using this endpoint:
     * - Ensure that the energy usage record exists in the system.
     *
     * After using this endpoint:
     * - You will receive detailed information about the selected energy usage record.
     *
     * @apiResource App\Http\Resources\EnergyUsageResource
     * @apiResourceModel App\Models\EnergyUsage
     *
     * @response 200 {
     *   "id": 1,
     *   "machine_id": 1,
     *   "energy_consumed": 123.45,
     *   "created_at": "2024-10-18T10:00:00.000000Z",
     *   "updated_at": "2024-10-18T10:00:00.000000Z"
     * }
     * @response 404 {"message": "Not found"}
     */
    public function show(int $energy_usage_id)
    {
        try {
            $energyUsage = EnergyUsage::findOrFail($energy_usage_id);
            return response()->json($energyUsage, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Create a New Energy Usage Record
     *
     * Add a new energy usage record associated with a specific machine.
     *
     * Before using this endpoint:
     * - Ensure the machine exists and is valid.
     * - Provide necessary energy usage details.
     *
     * After using this endpoint:
     * - A new energy usage record will be created and associated with the machine.
     *
     * @apiResource App\Http\Resources\EnergyUsageResource
     * @apiResourceModel App\Models\EnergyUsage
     *
     * @response 201 {
     *   "id": 1,
     *   "machine_id": 1,
     *   "energy_consumed": 123.45,
     *   "created_at": "2024-10-18T10:00:00.000000Z",
     *   "updated_at": "2024-10-18T10:00:00.000000Z"
     * }
     * @response 404 {"message": "Not found"}
     */
    public function store(StoreEnergyUsageRequest $request,int $machine_id)
    {
        try {
            DB::beginTransaction();

            Machine::findOrFail($machine_id);

            $usage = EnergyUsage::create(
                $request->validated()
                    + [
                        'machine_id' => $machine_id,
                    ]
            );

            DB::commit();
            return response()->json($usage, 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Update an Existing Energy Usage Record
     *
     * Modify the details of an existing energy usage record.
     *
     * Before using this endpoint:
     * - Ensure that the energy usage record exists.
     * - Provide updated energy usage details.
     *
     * After using this endpoint:
     * - The energy usage record's details will be updated in the system.
     *
     * @apiResource App\Http\Resources\EnergyUsageResource
     * @apiResourceModel App\Models\EnergyUsage
     *
     * @response 200 {
     *   "id": 1,
     *   "machine_id": 1,
     *   "energy_consumed": 150.75,
     *   "created_at": "2024-10-18T10:00:00.000000Z",
     *   "updated_at": "2024-10-18T10:10:00.000000Z"
     * }
     * @response 404 {"message": "Not found"}
     */
    public function update(UpdateEnergyUsageRequest $request, int $energy_usage_id)
    {
        try {
            DB::beginTransaction();

            $usage = EnergyUsage::findOrFail($energy_usage_id);
            $usage->update($request->validated());

            DB::commit();
            return response()->json($usage, 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Delete an Energy Usage Record
     *
     * Permanently remove an energy usage record from the system.
     *
     * Before using this endpoint:
     * - Ensure that the energy usage record exists.
     *
     * After using this endpoint:
     * - The energy usage record will be removed from the system.
     *
     * @response 204 {"message": "Energy usage record deleted"}
     * @response 404 {"message": "Not found"}
     */
    public function destroy(int $energy_usage_id)
    {
        try {
            DB::beginTransaction();

            $energyUsage = EnergyUsage::findOrFail($energy_usage_id);
            $energyUsage->delete();

            DB::commit();
            return response()->json(['message' => 'Defect deleted'], 204);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }
}
