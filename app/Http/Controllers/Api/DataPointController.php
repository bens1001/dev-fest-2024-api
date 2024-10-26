<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\DataPoint;
use App\Http\Controllers\Controller;

class DataPointController extends Controller
{
    /**
     * Get all data points, with optional filters.
     */
    public function index(Request $request)
    {
        $query = DataPoint::query();

        // Filter by KPI name
        if ($request->has('kpi_name')) {
            $query->where('kpi_name', $request->input('kpi_name'));
        }

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('timestamp', [$request->input('start_date'), $request->input('end_date')]);
        }

        // Pagination or limit
        $limit = $request->input('limit', 50); // Default to 50 per page if limit not specified
        $dataPoints = $query->paginate($limit);

        return response()->json($dataPoints);
    }

    /**
     * Get a specific data point by ID.
     */
    public function show($id)
    {
        $dataPoint = DataPoint::findOrFail($id);

        return response()->json($dataPoint);
    }
}
