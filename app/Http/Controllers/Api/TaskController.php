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
    /**
     * List Tasks
     *
     * Retrieve a paginated list of tasks, with optional filtering by status.
     *
     * Before using this endpoint:
     * - Ensure tasks are available.
     *
     * After using this endpoint:
     * - You will receive a paginated list of tasks, optionally filtered by status.
     *
     * @apiResourceCollection App\Http\Resources\TaskResource
     * @apiResourceModel App\Models\Task paginate=10
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Task 1",
     *       "status": "pending",
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

    /**
     * Show a Task
     *
     * Retrieve details of a specific task by ID.
     *
     * Before using this endpoint:
     * - Ensure that the task exists.
     *
     * After using this endpoint:
     * - You will receive the details of the selected task.
     *
     * @apiResource App\Http\Resources\TaskResource
     * @apiResourceModel App\Models\Task
     *
     * @response 200 {
     *   "id": 1,
     *   "title": "Task 1",
     *   "status": "pending",
     *   "created_at": "2024-10-18T10:00:00.000000Z",
     *   "updated_at": "2024-10-18T10:00:00.000000Z"
     * }
     * @response 404 {"message": "Not found"}
     */
    public function show(int $task_id)
    {
        try {
            $task = Task::findOrFail($task_id);
            return new TaskResource($task);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Update a Task
     *
     * Update the details of an existing task.
     *
     * Before using this endpoint:
     * - Ensure that the task exists.
     *
     * After using this endpoint:
     * - The task will be updated with the new details.
     *
     * @apiResource App\Http\Resources\TaskResource
     * @apiResourceModel App\Models\Task
     *
     * @response 200 {
     *   "id": 1,
     *   
     *   "title": "Updated Task",
     *   "status": "completed",
     *   "created_at": "2024-10-18T10:00:00.000000Z",
     *   "updated_at": "2024-10-18T10:00:00.000000Z"
     * }
     * @response 404 {"message": "Not found"}
     */
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

    /**
     * Delete a Task
     *
     * Permanently remove a task from the system.
     *
     * Before using this endpoint:
     * - Ensure that the task exists.
     *
     * After using this endpoint:
     * - The task will be deleted from the system.
     *
     * @response 200 {"message": "Task deleted"}
     * @response 404 {"message": "Not found"}
     */
    public function destroy(int $task_id)
    {
        try {
            DB::beginTransaction();

            $task = Task::findOrFail($task_id);
            $task->delete();

            DB::commit();
            return response()->json(['message' => 'Task deleted'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }
}
