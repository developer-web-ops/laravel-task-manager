<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public auth routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

// Protected routes (Laravel Passport)
Route::middleware('auth:api')->group(function () {

    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('logout',         [AuthController::class, 'logout']);
        Route::get('profile',         [AuthController::class, 'profile']);
        Route::put('profile',         [AuthController::class, 'updateProfile']);
    });

    // Tasks
    Route::prefix('tasks')->group(function () {
        Route::get('/',         [TaskController::class, 'index']);
        Route::post('/',        [TaskController::class, 'store']);
        Route::get('/stats',    [TaskController::class, 'stats']);
        Route::get('/{task}',   [TaskController::class, 'show']);
        Route::put('/{task}',   [TaskController::class, 'update']);
        Route::delete('/{task}',[TaskController::class, 'destroy']);

        // Bulk operations
        Route::post('/bulk/update', [TaskController::class, 'bulkUpdate']);
        Route::post('/bulk/delete', [TaskController::class, 'bulkDelete']);
    });
});
