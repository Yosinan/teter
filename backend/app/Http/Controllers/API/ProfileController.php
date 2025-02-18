<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class ProfileController extends Controller
{
    /**
     * Display the user's profile information.
     */
    public function edit(Request $request): JsonResponse
    {
        return response()->json([
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): JsonResponse
    {
        try {

        Log::info('Profile update request from the frontend', ['data' => $request->all()]);
        $user = $request->user();
        
        $validatedData = $request->validate([
            'first_name' => 'nullable', 'string', 'max:255',
            'last_name' => 'nullable','string', 'max:255',
            'avatar' => 'image', 'max:1024',
            'email' => 'nullable', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id),
        ]);

        
        // Update user details
        if ($request->filled('first_name')) {
            $user->first_name = $validatedData['first_name'];
        }

        if ($request->filled('last_name')) {
            $user->last_name = $validatedData['last_name'];
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            // Store new avatar
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('avatars', $filename, 'public');
            $user->avatar = $filename;
        }

        // Save user
        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => $user,
        ]);
    } catch (\Exception $e) 
        {
            Log::error('An error occurred', ['message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Revoke all tokens for the user
        $user->tokens()->delete();

        $user->delete();

        return response()->json([
            'message' => 'Account deleted successfully.',
        ]);
        }
        catch (\Exception $e) {
            Log::error('An error occurred', ['message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}