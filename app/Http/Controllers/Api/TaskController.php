<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\TaskResource;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Task;

/**
 * @group Task
 *
 * APIs for Task management.
 */
class TaskController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Task::query();

            // Optional filtering
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // You can add more filters here as needed
            $tasks = $query->paginate($request->per_page ?? 10);

            return TaskResource::collection($tasks);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    public function show(int $task_id)
    {
        try {
            $task = Task::findOrFail($task_id);
            return new TaskResource($task);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    public function update(UpdateTaskRequest $request, int $task_id)
    {
        try {
            DB::beginTransaction();

            $task = Task::findOrFail($task_id);
            $task->update($request->validated());

            DB::commit();
            return new TaskResource($task);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    public function destroy(int $task_id)
    {
        try {
            DB::beginTransaction();

            $task = Task::findOrFail($task_id);
            $task->delete();

            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }
}
