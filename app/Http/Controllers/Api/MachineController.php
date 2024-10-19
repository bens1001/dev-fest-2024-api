<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Machine;
use App\Http\Resources\MachineResource;
use App\Http\Requests\Machine\StoreMachineRequest;
use App\Http\Requests\Machine\UpdateMachineRequest;
use Illuminate\Support\Facades\DB;

/**
 * @group Machine
 *
 * APIs for Machine management.
 */
class MachineController extends Controller
{
    /**
     * List Machines
     *
     * Retrieve a paginated list of machines, with optional filtering by status and machine type.
     *
     * Before using this endpoint:
     * - Ensure machines have been added to the system.
     *
     * After using this endpoint:
     * - You will get a list of machines, optionally filtered by status or machine type.
     *
     * @apiResourceCollection App\Http\Resources\MachineResource
     * @apiResourceModel App\Models\Machine paginate=10
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Machine A",
     *       "status": "active",
     *       "machine_type": "type1",
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
            $query = Machine::query();

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('machine_type')) {
                $query->where('machine_type', 'LIKE', '%' . $request->machine_type . '%');
            }

            $machines = $query->paginate($request->per_page ?? 10);

            return MachineResource::collection($machines);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Show a Machine
     *
     * Retrieve details of a specific machine by ID.
     *
     * Before using this endpoint:
     * - Ensure that the machine exists in the system.
     *
     * After using this endpoint:
     * - You will receive detailed information about the selected machine.
     *
     * @apiResource App\Http\Resources\MachineResource
     * @apiResourceModel App\Models\Machine
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "Machine A",
     *   "status": "active",
     *   "machine_type": "type1",
     *   "created_at": "2024-10-18T10:00:00.000000Z",
     *   "updated_at": "2024-10-18T10:00:00.000000Z"
     * }
     * @response 404 {"message": "Not found"}
     */
    public function show(int $machine_id)
    {
        try {
            $machine = Machine::findOrFail($machine_id);
            return new MachineResource($machine);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Create a New Machine
     *
     * Add a new machine to the system.
     *
     * Before using this endpoint:
     * - Provide necessary machine details (name, status, machine type, etc.).
     *
     * After using this endpoint:
     * - A new machine will be created in the system.
     *
     * @apiResource App\Http\Resources\MachineResource
     * @apiResourceModel App\Models\Machine
     *
     * @response 201 {
     *   "id": 1,
     *   "name": "Machine A",
     *   "status": "active",
     *   "machine_type": "type1",
     *   "created_at": "2024-10-18T10:00:00.000000Z",
     *   "updated_at": "2024-10-18T10:00:00.000000Z"
     * }
     * @response 404 {"message": "Not found"}
     */
    public function store(StoreMachineRequest $request)
    {
        try {
            DB::beginTransaction();

            $machine = Machine::create($request->validated());

            DB::commit();
            return new MachineResource($machine);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Update an Existing Machine
     *
     * Modify the details of an existing machine by its ID.
     *
     * Before using this endpoint:
     * - Ensure that the machine exists.
     * - Provide updated machine details.
     *
     * After using this endpoint:
     * - The machine's details will be updated in the system.
     *
     * @apiResource App\Http\Resources\MachineResource
     * @apiResourceModel App\Models\Machine
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "Machine A",
     *   "status": "inactive",
     *   "machine_type": "type2",
     *   "created_at": "2024-10-18T10:00:00.000000Z",
     *   "updated_at": "2024-10-18T10:10:00.000000Z"
     * }
     * @response 404 {"message": "Not found"}
     */
    public function update(UpdateMachineRequest $request, int $machine_id)
    {
        try {
            DB::beginTransaction();

            $machine = Machine::findOrFail($machine_id);
            $machine->update($request->validated());

            DB::commit();
            return new MachineResource($machine);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Delete a Machine
     *
     * Permanently remove a machine from the system.
     *
     * Before using this endpoint:
     * - Ensure that the machine exists.
     *
     * After using this endpoint:
     * - The machine will be removed from the system.
     *
     * @response 204 {"message": "Machine deleted"}
     * @response 404 {"message": "Not found"}
     */
    public function destroy(int $machine_id)
    {
        try {
            DB::beginTransaction();

            $machine = Machine::findOrFail($machine_id);
            $machine->delete();

            DB::commit();
            return response()->json(['message' => 'Defect deleted'], 204);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }
}
