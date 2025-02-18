<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class PasswordResetLinkController extends Controller
{
    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        try {
            // Validate the email input
            $request->validate([
                'email' => 'required|email',
            ]);

            // Attempt to send the password reset link
            $status = Password::sendResetLink(
                $request->only('email')
            );

            // Return success response if the link was sent
            if ($status == Password::RESET_LINK_SENT) {
                return response()->json(['message' => __($status)], 200);
            }

            // Return error response if the email was invalid or sending failed
            throw ValidationException::withMessages([
                'email' => [trans($status)],
            ]);
        } catch (ValidationException $e) {
            // Return a validation error response
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Return a general error response
            return response()->json(['error' => 'An error occurred while sending the password reset link'], 500);
        }
    }
}