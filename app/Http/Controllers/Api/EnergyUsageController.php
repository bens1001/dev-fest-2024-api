<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnergyUsage\StoreEnergyUsageRequest;
use App\Http\Requests\EnergyUsage\UpdateEnergyUsageRequest;
use Illuminate\Support\Facades\DB;
use App\Models\EnergyUsage;
use Illuminate\Http\Request;

/**
 * @group Energy Usage
 *
 * APIs for Energy Usage management.
 */
class EnergyUsageController extends Controller
{
    public function index(Request $request)
    {
        try {
            $energyUsage = EnergyUsage::paginate($request->per_page ?? 10);

            return response()->json($energyUsage, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }


    public function show(int $energy_usage_id)
    {
        try {
            $energyUsage = EnergyUsage::findOrFail($energy_usage_id);
            return response()->json($energyUsage, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }
    public function store(StoreEnergyUsageRequest $request)
    {
        try {
            DB::beginTransaction();

            $usage = EnergyUsage::create($request->validated());

            DB::commit();
            return response()->json($usage, 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }

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

    public function destroy(int $energy_usage_id)
    {
        try {
            DB::beginTransaction();

            $energyUsage = EnergyUsage::findOrFail($energy_usage_id);
            $energyUsage->delete();

            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }
}
