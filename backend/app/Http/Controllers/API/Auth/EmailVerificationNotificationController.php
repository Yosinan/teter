<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification via API.
     */
    public function sendVerificationNotification(Request $request)
    {
        // Check if the user has already verified their email
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email is already verified.',
            ], 200);
        }

        // Send the email verification notification
        $request->user()->sendEmailVerificationNotification();

        // Return a success response
        return response()->json([
            'message' => 'Verification link sent successfully.',
        ], 200);
    }
}
