<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class ConfirmablePasswordController extends Controller
{
    /**
     * Confirm the user's password via API.
     */
    public function confirmPassword(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'password' => ['required', 'string'],
            ]);

            // Get the authenticated user
            $user = $request->user();

            // Check if the user's password is valid
            if (! Hash::check($request->password, $user->password)) {
                // Return an error response if the password is invalid
                return response()->json(['message' => 'The provided password is incorrect.'], 422);
            }

            // Return a success response
            return response()->json([
                'message' => 'Password confirmed successfully.',
                'confirmed_at' => now()->timestamp,
            ], 200);
        } catch (ValidationException $e) {
            // Log the validation error
            Log::error('Validation error', ['errors' => $e->errors()]);
            // Return a validation error response
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Log the general error
            Log::error('An error occurred while confirming the password', ['exception' => $e]);
            // Return a general error response
            return response()->json(['error' => 'An error occurred while confirming the password'], 500);
        }
    }
}