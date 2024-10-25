<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



/**
 * @group User
 *
 * APIs for User management.
 */
class UserController extends Controller
{
    /**
     * List Users
     *
     * Retrieve a paginated list of users, with optional filtering by gender.
     *
     * Before using this endpoint:
     * - Ensure users are available.
     *
     * After using this endpoint:
     * - You will receive a paginated list of users, optionally filtered by gender.
     *
     * @apiResourceCollection App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User paginate=10
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "john@example.com",
     *       "gender": "male",
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
            $query = User::query();

            // Optional filtering
            if ($request->has('gender')) {
                $query->where('gender', $request->gender);
            }

            // Optional search by full_name with partial match
            if ($request->has('full_name')) {
                $query->where('full_name', 'LIKE', '%' . $request->full_name . '%');
            }

            // You can add more filters here as needed
            $users = $query->paginate($request->per_page ?? 10);

            return UserResource::collection($users);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Show a User
     *
     * Retrieve details of a specific user by ID.
     *
     * Before using this endpoint:
     * - Ensure that the user exists.
     *
     * After using this endpoint:
     * - You will receive the details of the selected user.
     *
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "John Doe",
     *   "email": "john@example.com",
     *   "gender": "male",
     *   "created_at": "2024-10-18T10:00:00.000000Z",
     *   "updated_at": "2024-10-18T10:00:00.000000Z"
     * }
     * @response 404 {"message": "Not found"}
     */
    public function show(int $user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            return new UserResource($user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Store a User
     *
     * Create a new user with the provided details.
     *
     * Before using this endpoint:
     * - Ensure the request data is valid.
     *
     * After using this endpoint:
     * - The new user will be created and returned.
     *
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     *
     * @response 201 {
     *   "id": 1,
     *   "name": "John Doe",
     *   "email": "john@example.com",
     *   "gender": "male",
     *   "created_at": "2024-10-18T10:00:00.000000Z",
     *   "updated_at": "2024-10-18T10:00:00.000000Z"
     * }
     * @response 404 {"message": "Not found"}
     */
    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();

            // Get the currently authenticated user (creator)
            $creator = request()->user();

            // Retrieve the role from the request
            $role = $request->validated()['role'];


            if ($creator->hasRole('manager')) {
                // Manager can only assign 'operator' role
                if ($role !== 'operator') {
                    return response()->json(['message' => 'Invalid role. Manager can only assign operator role.'], 403);
                }
            } else if ($creator->hasRole('operator')) {
                // If the creator doesn't have permission to assign roles, return an error
                return response()->json(['message' => 'You do not have permission to assign roles.'], 403);
            }

            // Create the user
            $user = User::create($request->validated());

            // Assign the validated role to the new user
            $user->assignRole($role);

            DB::commit();
            return new UserResource($user);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to create user: ' . $e->getMessage()], 500);
        }
    }



    /**
     * Update a User
     *
     * Update the details of an existing user.
     *
     * Before using this endpoint:
     * - Ensure that the user exists.
     *
     * After using this endpoint:
     * - The user's details will be updated with the provided data.
     *
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "Updated John Doe",
     *   "email": "john.updated@example.com",
     *   "gender": "male",
     *   "created_at": "2024-10-18T10:00:00.000000Z",
     *   "updated_at": "2024-10-18T10:00:00.000000Z"
     * }
     * @response 404 {"message": "Not found"}
     */
    public function update(UpdateUserRequest $request, int $user_id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($user_id);
            $user->update($request->validated());

            DB::commit();
            return new UserResource($user);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Delete a User
     *
     * Permanently remove a user from the system.
     *
     * Before using this endpoint:
     * - Ensure that the user exists.
     *
     * After using this endpoint:
     * - The user will be deleted from the system.
     *
     * @response 200 {"message": "User deleted"}
     * @response 404 {"message": "Not found"}
     */
    public function destroy(int $user_id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($user_id);
            $user->delete();

            DB::commit();
            return response()->json(['message' => 'User deleted'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }
}
