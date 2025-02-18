<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        try {
            // Find the user by email
            $user = User::where('email', $request->email)->first();

            // Check if the user exists
            if(!$user) {
                return response()->json(['message' => 'User not found.'], 404);
            }
            // Check if the user is an admin by email
            if ($user && $user->email === 'admin@gmail.com') {
                if (Hash::check($request->password, $user->password)) {
                    $request->authenticate();

                    $token = $user->createToken('auth_token')->plainTextToken;

                    return response()->json([
                        'message' => 'Logged in successfully as admin',
                        'token' => $token,
                        'token_type' => 'Bearer',
                    ]);
                } else {
                    // Authentication failed
                    return response()->json(['message' => 'Invalid email or password.'], 401);
                }
            }

            // Check if the user exists and the password is correct
            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    $request->authenticate();

                    $token = $user->createToken('auth_token')->plainTextToken;

                    return response()->json([
                        'message' => 'Logged in successfully',
                        'token' => $token,
                        'token_type' => 'Bearer',
                    ]);
                } else {
                    // Authentication failed
                    return response()->json(['message' => 'Invalid email or password.'], 401);
                }
            }
           
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred during login',
            ], 500);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            // Revoke all tokens for the user
            $user->tokens()->delete();

            return response()->json([
                'message' => 'Logged out successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred during logout',
            ], 500);
        }
    }
}
