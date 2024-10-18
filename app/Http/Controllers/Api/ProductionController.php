<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Production;
use App\Http\Resources\ProductionResource;
use App\Http\Requests\Production\StoreProductionRequest;
use App\Http\Requests\Production\UpdateProductionRequest;
use Illuminate\Support\Facades\DB;

/**
 * @group Production
 *
 * APIs for Production management.
 */
class ProductionController extends Controller
{
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

    public function show(int $production_id)
    {
        try {
            $production = Production::findOrFail($production_id);
            return new ProductionResource($production);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    public function store(StoreProductionRequest $request)
    {
        try {
            DB::beginTransaction();

            $production = Production::create($request->validated());

            DB::commit();
            return new ProductionResource($production);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }

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

    public function destroy(int $production_id)
    {
        try {
            DB::beginTransaction();

            $production = Production::findOrFail($production_id);
            $production->delete();

            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }
}
