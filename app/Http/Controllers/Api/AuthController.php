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
    /**
     * User Login
     *
     * Authenticate a user and generate a new authentication token.
     *
     * ### Before using this endpoint:
     * - Ensure the user is registered in the system.
     * - Provide valid email and password credentials.
     *
     * ### After using this endpoint:
     * - A token will be generated for the user, which can be used for authenticated requests.
     *
     * @response 200 {
     *   "token": "1|abc123tokenxyz",
     *   "role": "admin"
     * }
     * @response 401 {"message": "Unauthorized"}
     */
    public function login(LoginRequest $request) // Using a custom request for validation
    {
        $credentials = $request->validated();
        $user = User::where('email', $credentials['email'])->firstOrFail();

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        // Retrieve the user's role
        $userRole = $user->getRoleNames();

        return response()->json([
            'token' => $token,
            'role' => $userRole->first(),
            'user' => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'gender' => $user->gender,
            ]
        ], 200);
    }

    /**
     * User Logout
     *
     * Log out the user and delete their active tokens.
     *
     * ### Before using this endpoint:
     * - Ensure the user is logged in.
     *
     * ### After using this endpoint:
     * - The user's tokens will be revoked, logging them out.
     *
     * @response 200 {"message": "Logged out"}
     * @response 404 {"message": "User not found"}
     */
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

    /**
     * Change User Password
     *
     * Update the user's password after verifying the current password.
     *
     * ### Before using this endpoint:
     * - Provide the user's current password for verification.
     * - Provide the new password.
     *
     * ### After using this endpoint:
     * - The user's password will be updated to the new one.
     *
     * @bodyParam email string required The user's email address. Example: user@example.com
     * @bodyParam current_password string required The user's current password. Example: secret123
     * @bodyParam new_password string required The user's new password. Example: newsecret456
     *
     * @response 200 {"message": "Password changed successfully"}
     * @response 400 {"message": "Current password is incorrect"}
     * @response 404 {"message": "User not found"}
     */
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
