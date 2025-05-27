<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\Department\DepartmentController;
use App\Http\Controllers\Api\Request\RequestController;
use App\Http\Controllers\Api\Approver\ApproverController;


Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('refresh-token', [AuthController::class, 'refreshToken']);
});

// Authenticated routes
Route::apiResource('departments', DepartmentController::class);

  // Approver hierarchy route
  Route::prefix('departments/{department}')->group(function () {
    Route::get('approvers', [ApproverController::class, 'index']);
    Route::post('approvers', [ApproverController::class, 'store']);
    Route::put('approvers/{approver}', [ApproverController::class, 'update']);
    Route::delete('approvers/{approver}', [ApproverController::class, 'destroy']);
});


Route::middleware(['auth:api'])->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });

    // User management routes
    Route::apiResource('users', UserController::class)->except(['create', 'edit']);

    // Department routes

  

    // Request routes
    Route::prefix('requests')->group(function () {
        Route::get('/', [RequestController::class, 'index']);
        Route::get('pending', [RequestController::class, 'pending']);
        Route::post('/', [RequestController::class, 'store']);
        Route::get('{request}', [RequestController::class, 'show']);
        
        // Approval actions
        Route::prefix('{request}')->group(function () {
            Route::post('approve', [RequestController::class, 'approve']);
            Route::post('reject', [RequestController::class, 'reject']);
            Route::get('history', [RequestController::class, 'history']);
        });
    });
});