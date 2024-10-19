<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Defect;
use App\Models\Machine;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\DefectResource;
use App\Http\Requests\Defect\StoreDefectRequest;
use App\Http\Requests\Defect\UpdateDefectRequest;
use App\Models\Alert;
use App\Models\Task;

/**
 * @group Defect
 *
 * APIs for Defect management.
 */
class DefectController extends Controller
{
    /**
     * List Defects
     *
     * Retrieve a paginated list of defects.
     *
     * Before using this endpoint:
     * - Make sure defects have been logged in the system.
     *
     * After using this endpoint:
     * - You will get a list of defects, filtered as needed.
     *
     * @apiResourceCollection App\Http\Resources\DefectResource
     * @apiResourceModel App\Models\Defect paginate=10
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Defect 1",
     *       "machine_id": 1,
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
            $query = Defect::query();

            // You can add more filters here as needed
            $defects = $query->paginate($request->per_page ?? 10);

            return DefectResource::collection($defects);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Show a Defect
     *
     * Retrieve the details of a specific defect by ID.
     *
     * Before using this endpoint:
     * - Ensure that the defect exists in the system.
     *
     * After using this endpoint:
     * - You will receive detailed information about the selected defect.
     *
     * @apiResource App\Http\Resources\DefectResource
     * @apiResourceModel App\Models\Defect
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "Defect 1",
     *   "machine_id": 1,
     *   "created_at": "2024-10-18T10:00:00.000000Z",
     *   "updated_at": "2024-10-18T10:00:00.000000Z"
     * }
     * @response 404 {"message": "Not found"}
     */
    public function show(int $defect_id)
    {
        try {
            $defect = Defect::findOrFail($defect_id);
            return new DefectResource($defect);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Create a New Defect
     *
     * Add a new defect associated with a specific machine.
     *
     * Before using this endpoint:
     * - Ensure that the machine exists and is valid.
     * - Provide necessary defect details.
     *
     * After using this endpoint:
     * - A new defect will be created and associated with the machine.
     *
     * @apiResource App\Http\Resources\DefectResource
     * @apiResourceModel App\Models\Defect
     *
     * @response 201 {
     *   "id": 1,
     *   "name": "Defect 1",
     *   "machine_id": 1,
     *   "created_at": "2024-10-18T10:00:00.000000Z",
     *   "updated_at": "2024-10-18T10:00:00.000000Z"
     * }
     * @response 404 {"message": "Not found"}
     */
    public function store(StoreDefectRequest $request, int $machine_id)
    {
        try {
            DB::beginTransaction();

            // Ensure the machine exists
            Machine::findOrFail($machine_id);

            // Create the defect
            $defect = Defect::create(
                $request->validated() + [
                    'machine_id' => $machine_id,
                ]
            );

            // Create an alert for the defect
            $alert = Alert::create([
                'machine_id' => $machine_id,
                'alert_message' => 'New defect of type ' . $defect->defect_type . '.',
                'alert_time' => now(),
            ]);

            // Create a task to fix the defect
            Task::create([
                'machine_id' => $machine_id,
                'task_description' => 'Fix the defect of type: ' . $defect->defect_type,
                'status' => 'todo',
            ]);

            DB::commit();
            return new DefectResource($defect);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found or an error occurred: ' . $e->getMessage()], 404);
        }
    }

    /**
     * Update an Existing Defect
     *
     * Modify the details of an existing defect.
     *
     * Before using this endpoint:
     * - Ensure that the defect exists.
     * - Provide updated defect details.
     *
     * After using this endpoint:
     * - The defect's details will be updated in the system.
     *
     * @apiResource App\Http\Resources\DefectResource
     * @apiResourceModel App\Models\Defect
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "Updated Defect 1",
     *   "machine_id": 1,
     *   "created_at": "2024-10-18T10:00:00.000000Z",
     *   "updated_at": "2024-10-18T10:10:00.000000Z"
     * }
     * @response 404 {"message": "Not found"}
     */
    public function update(UpdateDefectRequest $request, int $defect_id)
    {
        try {
            DB::beginTransaction();

            $defect = Defect::findOrFail($defect_id);
            $defect->update($request->validated());

            DB::commit();
            return new DefectResource($defect);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Delete a Defect
     *
     * Permanently remove a defect from the system.
     *
     * Before using this endpoint:
     * - Ensure that the defect exists.
     *
     * After using this endpoint:
     * - The defect will be removed from the system.
     *
     * @response 200 {"message": "Defect deleted"}
     * @response 404 {"message": "Not found"}
     */
    public function destroy(int $defect_id)
    {
        try {
            DB::beginTransaction();

            $defect = Defect::findOrFail($defect_id);
            $defect->delete();

            DB::commit();
            return response()->json(['message' => 'Defect deleted'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }
}
