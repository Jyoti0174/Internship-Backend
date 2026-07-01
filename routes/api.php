<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DepartmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// Public routes - Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public ticket stats
Route::get('/tickets/stats', [TicketController::class, 'stats']);

// Protected routes - Sanctum middleware
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Ticket Assignment
    Route::get('/tickets/assigned-to-me', [TicketController::class, 'assignedToMe']);
    Route::put('/tickets/{id}/assign', [TicketController::class, 'assign']);
    Route::put('/tickets/{id}/unassign', [TicketController::class, 'unassign']);
    Route::patch('/tickets/{id}/status', [TicketController::class, 'updateStatus']);

    // Tickets
    Route::apiResource('tickets', TicketController::class);

    // Users
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::get('/users/{id}/tickets', [UserController::class, 'tickets']);

    // Departments
    Route::get('/departments', [DepartmentController::class, 'index']);
    Route::post('/departments', [DepartmentController::class, 'store']);
    Route::get('/departments/{id}', [DepartmentController::class, 'show']);
    Route::put('/departments/{id}', [DepartmentController::class, 'update']);
    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy']);

    // Comments
Route::get('/tickets/{ticketId}/comments', [CommentController::class, 'index']);
Route::post('/tickets/{ticketId}/comments', [CommentController::class, 'store']);
Route::put('/tickets/{ticketId}/comments/{commentId}', [CommentController::class, 'update']);
Route::delete('/tickets/{ticketId}/comments/{commentId}', [CommentController::class, 'destroy']);

Route::get('/tickets/{id}/attachments', [AttachmentController::class, 'index']);
Route::post('/tickets/{id}/attachments', [AttachmentController::class, 'store']);
Route::get('/attachments/{id}/download', [AttachmentController::class, 'download']);
Route::delete('/tickets/{id}/attachments/{attachmentId}', [AttachmentController::class, 'destroy']);

    // Activity Logs
    Route::get('/tickets/{id}/activity-logs', [ActivityLogController::class, 'index']);
    // Notification Preferences
Route::get('/user/notification-preferences', [AuthController::class, 'getNotificationPreferences']);
Route::put('/user/notification-preferences', [AuthController::class, 'updateNotificationPreferences']);

});