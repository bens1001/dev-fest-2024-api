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

    public function show(int $machine_id)
    {
        try {
            $machine = Machine::findOrFail($machine_id);
            return new MachineResource($machine);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

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

    public function destroy(int $machine_id)
    {
        try {
            DB::beginTransaction();

            $machine = Machine::findOrFail($machine_id);
            $machine->delete();

            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }
}
