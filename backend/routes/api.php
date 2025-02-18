<?php

use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\Auth\PasswordController;
use App\Http\Controllers\API\Auth\NewPasswordController;
use App\Http\Controllers\API\Auth\VerifyEmailController;
use App\Http\Controllers\API\Auth\RegisteredUserController;
use App\Http\Controllers\API\Auth\PasswordResetLinkController;
use App\Http\Controllers\API\Auth\ConfirmablePasswordController;
use App\Http\Controllers\API\Auth\AuthenticatedSessionController;
use App\Http\Controllers\API\Auth\EmailVerificationPromptController;
use App\Http\Controllers\API\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\TaskController;

Route::get('/user', function (Request $request) {
    $user = $request->user();
    $avatarPath = $user->avatar ? asset('storage/avatars/' . $user->avatar) : null;
    $user->avatar = $avatarPath;
    return response()->json($user);

})->middleware('auth:sanctum');

Route::get('/status', function (Request $request): JsonResponse {
    return response()->json(
        [
        'status' => 'OK',
        'message' => 'Server is Up and Running. 🚀 '
    ]);
});

Route::middleware('guest')->group(function () {

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
});

// Authenticated user routes
Route::middleware('auth:sanctum')->group(function () {

    // Email verification routes
    Route::prefix('email')->group(function () {
        Route::post('/verification-notification', [EmailVerificationNotificationController::class, 'sendVerificationNotification']);
        Route::get('/verify', [VerifyEmailController::class, '__invoke']);
        Route::get('/verification-prompt', [EmailVerificationPromptController::class, '__invoke']);
    });

    // Password routes
    Route::prefix('password')->group(function () {
        // Password Confirmation
        Route::post('/confirm', [ConfirmablePasswordController::class, 'confirmPassword']);

        // Password Reset
        Route::post('/reset-link', [PasswordResetLinkController::class, 'store']);
        Route::get('/reset/{token}', [NewPasswordController::class, 'create']);
        Route::post('/reset', [NewPasswordController::class, 'store']);

        // Update Password
        Route::put('/password/update', [PasswordController::class, 'update']);
    });

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Profile routes
    Route::prefix('profile')->group(function () {
        // Get the user's profile information
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');

        // Update the user's profile information
        Route::post('/update', [ProfileController::class, 'update'])->name('profile.update');

        // Delete the user's account
        Route::delete('/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });


    // Task routes (accessible to all authenticated users)
    Route::prefix('tasks')->group(function () {
        Route::post('/', [TaskController::class, 'store']); // Create a task
        Route::get('/', [TaskController::class, 'index']); // List tasks (user-specific or all for admin)
        Route::put('/{task}', [TaskController::class, 'update']); // Update a task
        Route::delete('/{task}', [TaskController::class, 'destroy']); // Delete a task
    });
});


// Authenticated user with admin role routes (for admin only)
Route::middleware('auth:sanctum', 'role:admin')->group(function () {

    // Permissions routes
    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index']);
        Route::post('/', [PermissionController::class, 'store']);
        Route::get('/{id}', [PermissionController::class, 'show']);
        Route::put('/{id}', [PermissionController::class, 'update']);
        Route::delete('/{id}', [PermissionController::class, 'destroy']);
    });

    // Roles routes
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
        Route::put('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'destroy']);
        Route::get('/{id}/permissions', [RoleController::class, 'addPermissionToRole']);
        Route::post('/{id}/permissions', [RoleController::class, 'givePermissionToRole']);
    });
});

Route::get('/test', function () {
    return response()->json(['message' => 'Hello World!']);
});