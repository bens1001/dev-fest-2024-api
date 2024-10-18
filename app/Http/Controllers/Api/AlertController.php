<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Alert\UpdateAlertRequest;
use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\AlertResource;

/**
 * @group Alert
 *
 * APIs for Alert management.
 */
class AlertController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Alert::query();



            // You can add more filters here as needed
            $alerts = $query->paginate($request->per_page ?? 10);

            return AlertResource::collection($alerts);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }
    public function show(int $alert_id)
    {
        try {
            $alert = Alert::findOrFail($alert_id);
            return new AlertResource($alert);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }


    public function update(UpdateAlertRequest $request, int $alert_id)
    {
        try {
            DB::beginTransaction();

            $alert = Alert::findOrFail($alert_id);
            $alert->update($request->validated());

            DB::commit();
            return new AlertResource($alert);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    public function destroy(int $alert_id)
    {
        try {
            DB::beginTransaction();

            $alert = Alert::findOrFail($alert_id);
            $alert->delete();

            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }
}
