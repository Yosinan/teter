<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate the user's input
            $validatedData = $request->validate([
                'username' => 'required|string|max:255|unique:' . User::class,
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()], // Validates password and confirmation
            ]);

            // Hash the password before storing it in the database
            $validatedData['password'] = Hash::make($validatedData['password']);

            // Create a new user
            $user = User::create([
                'username' => $validatedData['username'],
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
            ]);

             // Assign the 'user' role by default
            $user->assignRole('user');

            event(new Registered($user));

            // Log success message
            Log::info('User registered successfully', $user->toArray());

            return response()->json([
                'message' => 'You have been registered successfully.',
                'user' => $user,
            ], 201); // Created status

        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in registration: ' . $e->getMessage());

            // Return a JSON error response
            return response()->json([
                'message' => 'There was an error registering. Please try again.',
                'error' => $e->getMessage(),
            ], 500); // Internal server error
        }
    }
}
