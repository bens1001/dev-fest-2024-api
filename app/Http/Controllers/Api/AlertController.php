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
    /**
     * List Alerts
     *
     * Fetch a list of alerts with optional pagination.
     *
     * Before using this endpoint:
     * - Ensure you are authenticated and have the required permissions.
     * - Ensure alerts exist in the system.
     *
     * After using this endpoint:
     * - You can view or manage the alerts fetched.
     *
     * Return a collection with pagination.
     * @apiResourceCollection App\Http\Resources\AlertResource
     * @apiResourceModel App\Models\Alert paginate=10
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Alert 1",
     *       "description": "Description of alert 1",
     *       "created_at": "2024-10-18T00:00:00.000000Z"
     *     },
     *     ...
     *   ],
     *   "links": {
     *     "first": "http://localhost/api/alerts?page=1",
     *     "last": "http://localhost/api/alerts?page=1",
     *     "prev": null,
     *     "next": null
     *   },
     *   "meta": {
     *     "current_page": 1,
     *     "from": 1,
     *     "last_page": 1,
     *     "path": "http://localhost/api/alerts",
     *     "per_page": 10,
     *     "to": 1,
     *     "total": 1
     *   }
     * }
     * @response 404 {"message": "Not found"}
     */
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

    /**
     * Show a Specific Alert
     *
     * Retrieve a specific alert by its ID.
     *
     * Before using this endpoint:
     * - Ensure the alert ID exists.
     *
     * After using this endpoint:
     * - You can view details of the selected alert.
     *
     * Return a single value.
     * @apiResource App\Http\Resources\AlertResource
     * @apiResourceModel App\Models\Alert
     *
     * @response 200 {
     *   "id": 1,
     *   "title": "Alert 1",
     *   "description": "Description of alert 1",
     *   "created_at": "2024-10-18T00:00:00.000000Z"
     * }
     * @response 404 {"message": "Not found"}
     */
    public function show(int $alert_id)
    {
        try {
            $alert = Alert::findOrFail($alert_id);
            return new AlertResource($alert);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Delete an Alert
     *
     * Deletes a specific alert by its ID.
     *
     * Before using this endpoint:
     * - Ensure the alert ID exists.
     * - Ensure you are authorized to delete alerts.
     *
     * After using this endpoint:
     * - The selected alert will be deleted from the system.
     *
     * @response 204 {"message": "Defect deleted"}
     * @response 404 {"message": "Not found"}
     */
    public function destroy(int $alert_id)
    {
        try {
            DB::beginTransaction();

            $alert = Alert::findOrFail($alert_id);
            $alert->delete();

            DB::commit();
            return response()->json(['message' => 'Defect deleted'], 204);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }
}
