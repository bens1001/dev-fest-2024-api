<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Production;
use App\Http\Resources\ProductionResource;
use App\Http\Requests\Production\StoreProductionRequest;
use App\Http\Requests\Production\UpdateProductionRequest;
use App\Models\Machine;
use Illuminate\Support\Facades\DB;

/**
 * @group Production
 *
 * APIs for Production management.
 */
class ProductionController extends Controller
{
    /**
     * List Productions
     *
     * Retrieve a paginated list of production records, with optional filtering by machine ID.
     *
     * Before using this endpoint:
     * - Ensure productions have been recorded.
     *
     * After using this endpoint:
     * - You will receive a list of production records, optionally filtered by machine ID.
     *
     * @apiResourceCollection App\Http\Resources\ProductionResource
     * @apiResourceModel App\Models\Production paginate=10
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "machine_id": 5,
     *       "machine_name": "Machine A",
     *       "machine_type": "Type X",
     *       "start_time": "2024-10-18T09:00:00.000000Z",
     *       "end_time": "2024-10-18T10:00:00.000000Z",
     *       "output_quantity": 1000,
     *       "target_quantity": 1200,
     *       "production_date": "2024-10-18",
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
            $query = Production::query();

            // Optional filtering
            if ($request->has('machine_id')) {
                $query->where('machine_id', $request->machine_id);
            }

            // You can add more filters here as needed
            $productions = $query->paginate($request->per_page ?? 10);

            return ProductionResource::collection($productions);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Show a Production Record
     *
     * Retrieve details of a specific production record by ID.
     *
     * Before using this endpoint:
     * - Ensure that the production record exists.
     *
     * After using this endpoint:
     * - You will receive detailed information about the selected production record.
     *
     * @apiResource App\Http\Resources\ProductionResource
     * @apiResourceModel App\Models\Production
     *
     * @response 200 {
     *   "id": 1,
     *   "machine_id": 5,
     *   "machine_name": "Machine A",
     *   "machine_type": "Type X",
     *   "start_time": "2024-10-18T09:00:00.000000Z",
     *   "end_time": "2024-10-18T10:00:00.000000Z",
     *   "output_quantity": 1000,
     *   "target_quantity": 1200,
     *   "production_date": "2024-10-18",
     *   "created_at": "2024-10-18T10:00:00.000000Z",
     *   "updated_at": "2024-10-18T10:00:00.000000Z"
     * }
     * @response 404 {"message": "Not found"}
     */
    public function show(int $production_id)
    {
        try {
            $production = Production::findOrFail($production_id);
            return new ProductionResource($production);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Create a New Production Record
     *
     * Add a new production record to the system.
     *
     * Before using this endpoint:
     * - Provide necessary details (machine ID, production date, quantity, etc.).
     * - The machine ID must correspond to an existing machine.
     *
     * After using this endpoint:
     * - A new production record will be created.
     *
     * @apiResource App\Http\Resources\ProductionResource
     * @apiResourceModel App\Models\Production
     *
     * @response 201 {
     *   "id": 1,
     *   "machine_id": 5,
     *   "machine_name": "Machine A",
     *   "machine_type": "Type X",
     *   "start_time": "2024-10-18T09:00:00.000000Z",
     *   "end_time": "2024-10-18T10:00:00.000000Z",
     *   "output_quantity": 1000,
     *   "target_quantity": 1200,
     *   "production_date": "2024-10-18",
     *   "created_at": "2024-10-18T10:00:00.000000Z",
     *   "updated_at": "2024-10-18T10:00:00.000000Z"
     * }
     * @response 404 {"message": "Not found"}
     */
    public function store(StoreProductionRequest $request, int $machine_id)
    {
        try {
            DB::beginTransaction();

            Machine::findOrFail($machine_id);

            $production = Production::create(
                $request->validated()
                    + [
                        'machine_id' => $machine_id,
                    ]
            );

            DB::commit();
            return new ProductionResource($production);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Update an Existing Production Record
     *
     * Modify the details of an existing production record by its ID.
     *
     * Before using this endpoint:
     * - Ensure that the production record exists.
     * - Provide updated production details.
     *
     * After using this endpoint:
     * - The production record's details will be updated in the system.
     *
     * @apiResource App\Http\Resources\ProductionResource
     * @apiResourceModel App\Models\Production
     *
     * @response 200 {
     *   "id": 1,
     *   "machine_id": 5,
     *   "machine_name": "Machine A",
     *   "machine_type": "Type X",
     *   "start_time": "2024-10-18T09:00:00.000000Z",
     *   "end_time": "2024-10-18T10:10:00.000000Z",
     *   "output_quantity": 1200,
     *   "target_quantity": 1200,
     *   "production_date": "2024-10-18",
     *   "created_at": "2024-10-18T10:00:00.000000Z",
     *   "updated_at": "2024-10-18T10:10:00.000000Z"
     * }
     * @response 404 {"message": "Not found"}
     */
    public function update(UpdateProductionRequest $request, int $production_id)
    {
        try {
            DB::beginTransaction();

            $production = Production::findOrFail($production_id);
            $production->update($request->validated());

            DB::commit();
            return new ProductionResource($production);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Delete a Production Record
     *
     * Permanently remove a production record from the system.
     *
     * Before using this endpoint:
     * - Ensure that the production record exists.
     *
     * After using this endpoint:
     * - The production record will be removed from the system.
     *
     * @response 200 {"message": "Production deleted"}
     * @response 404 {"message": "Not found"}
     */
    public function destroy(int $production_id)
    {
        try {
            DB::beginTransaction();

            $production = Production::findOrFail($production_id);
            $production->delete();

            DB::commit();
            return response()->json(['message' => 'Production deleted'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }
}
