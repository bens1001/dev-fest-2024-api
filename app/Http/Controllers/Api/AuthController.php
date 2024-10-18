<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest; // Assuming you have a request for login
use App\Http\Requests\Auth\ChangePasswordRequest; // Assuming you have a request for changing password
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @group Auth
 *
 * APIs for Auth management.
 */
class AuthController extends Controller
{
    public function login(LoginRequest $request) // Using a custom request for validation
    {
        $credentials = $request->validated();
        $user = User::where('email', $credentials['email'])->firstOrFail();

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    public function logout(int $user_id)
    {
        try {

            DB::beginTransaction();

            $user = User::findOrFail($user_id);
            $user->tokens()->delete();

            DB::commit();
            return response()->json(['message' => 'Logged out'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {

            DB::beginTransaction();

            $credentials = $request->validated();
            $user = User::where('email', $credentials['email'])->firstOrFail();

            // Verify current password
            if (!password_verify($credentials['current_password'], $user->password)) {
                return response()->json(['message' => 'Current password is incorrect'], 400);
            }

            // Update the user's password
            $user->password = bcrypt($credentials['new_password']);
            $user->save();

            DB::commit();
            return response()->json(['message' => 'Password changed successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Not found'], 404);
        }
    }
}
