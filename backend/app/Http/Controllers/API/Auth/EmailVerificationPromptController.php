<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt via API.
     */
    public function __invoke(Request $request)
    {
        // Check if the user's email is already verified
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email is already verified.',
                'redirect_to' => route('dashboard')
            ], 200);
        }

        // Return prompt message to verify email
        return response()->json([
            'message' => 'Please verify your email.',
            'status' => session('status'),
        ], 200);
    }
}
