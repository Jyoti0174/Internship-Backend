<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AuthController;

// Public routes - Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/tickets/stats', [TicketController::class, 'stats']);
 Updated upstream
Route::apiResource('tickets', TicketController::class);

// Protected routes - Sanctum middleware
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Tickets
    Route::apiResource('tickets', TicketController::class);

});
  15305da (install and configure Laravel Sanctum Authentication)

Route::apiResource('tickets', TicketController::class)->withoutMiddleware('auth:sanctum');
 Stashed changes
