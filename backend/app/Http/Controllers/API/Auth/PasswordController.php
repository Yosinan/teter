<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', Password::defaults(), 'confirmed'],
            ]);

            // Update the user's password
            $request->user()->update([
                'password' => Hash::make($validated['password']),
            ]);

            // Return a successful JSON response
            return response()->json(['message' => 'Password updated successfully'], 200);
        } catch (ValidationException $e) {
            // Return a validation error response
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Return a general error response
            return response()->json(['error' => 'An error occurred while updating the password'], 500);
        }
    }
}