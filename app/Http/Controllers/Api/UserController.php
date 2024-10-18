<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Support\Facades\DB;


/**
 * @group User
 *
 * APIs for User management.
 */
class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = User::query();

            // Optional filtering
            if ($request->has('gender')) {
                $query->where('gender', $request->gender);
            }

            // You can add more filters here as needed
            $users = $query->paginate($request->per_page ?? 10);

            return UserResource::collection($users);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    public function show(int $user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            return new UserResource($user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'full_name' => $request->validated()['full_name'],
                'password' => bcrypt($request->validated()['password']),
                'gender' => $request->validated()['gender'],
                'email' => $request->validated()['email'],
            ]);

            DB::commit();
            return new UserResource($user);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }

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

    public function destroy(int $user_id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($user_id);
            $user->delete();

            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }
}
