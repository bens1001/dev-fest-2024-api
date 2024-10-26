<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\DataPoint;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataPointResource;

/**
 * @group Data Point
 *
 * APIs for Data Points management.
 */
class DataPointController extends Controller
{
    /**
     * List Data Points
     *
     * Retrieve a list of data points with optional filters for KPI name, date range, and status.
     *
     * @queryParam kpi_name string Optional. Filter by KPI name. Example: "Stamping Press Efficiency".
     * @queryParam start_date date Optional. Start date for filtering by timestamp. Format: Y-m-d. Example: "2024-01-01".
     * @queryParam end_date date Optional. End date for filtering by timestamp. Format: Y-m-d. Example: "2024-12-31".
     * @queryParam status string Optional. Filter by status. Example: "active".
     * @queryParam per_page int Optional. Number of results per page. Defaults to 10.
     *
     * @response 200 {
     *     "data": [
     *         {
     *             "id": 1,
     *             "kpi_name": "Stamping Press Efficiency",
     *             "timestamp": "2024-10-25T10:00:00.000000Z",
     *             "status": "active",
     *             "value": 95.5
     *         }
     *     ],
     *     "links": {
     *         "first": "...",
     *         "last": "...",
     *         "prev": null,
     *         "next": "..."
     *     },
     *     "meta": {
     *         "current_page": 1,
     *         "from": 1,
     *         "last_page": 1,
     *         "path": "...",
     *         "per_page": 10,
     *         "to": 10,
     *         "total": 10
     *     }
     * }
     * @response 404 {
     *     "message": "Not found"
     * }
     */
    public function index(Request $request)
    {
        try {
            $query = DataPoint::query();

            // Filter by KPI name
            if ($request->has('kpi_name')) {
                $query->where('kpi_name', $request->input('kpi_name'));
            }

            // Filter by date range
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('timestamp', [$request->input('start_date'), $request->input('end_date')]);
            }

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            // Paginate and order results
            $dataPoints = $query->orderBy('timestamp', 'desc')->paginate($request->per_page ?? 10);

            return DataPointResource::collection($dataPoints);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Show Data Point
     *
     * Retrieve a specific data point by its ID.
     *
     * @urlParam id int required The ID of the data point to retrieve. Example: 1
     *
     * @response 200 {
     *     "data": {
     *         "id": 1,
     *         "kpi_name": "Stamping Press Efficiency",
     *         "timestamp": "2024-10-25T10:00:00.000000Z",
     *         "status": "active",
     *         "value": 95.5
     *     }
     * }
     * @response 404 {
     *     "message": "Not found"
     * }
     */
    public function show($id)
    {
        try {
            $dataPoint = DataPoint::findOrFail($id);

            return new DataPointResource($dataPoint);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }
}
