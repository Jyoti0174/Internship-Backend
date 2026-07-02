<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// Public routes - Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes - Sanctum middleware
Route::middleware('auth:sanctum')->group(function () {

    // Auth — sab access kar sakte hain
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Profile — sab roles apna profile dekh aur update kar sakte hain
    Route::get('/profile', [AuthController::class, 'me']);
    Route::put('/profile', [UserController::class, 'updateProfile']);

    // Notification Preferences — sab access kar sakte hain
    Route::get('/user/notification-preferences', [AuthController::class, 'getNotificationPreferences']);
    Route::put('/user/notification-preferences', [AuthController::class, 'updateNotificationPreferences']);

    // Sab roles — apne assigned tickets
    Route::get('/tickets/assigned-to-me', [TicketController::class, 'assignedToMe']);

    // Single ticket view — sab roles (controller mein employee check hoga)
    Route::get('/tickets/{id}', [TicketController::class, 'show']);

    // Status update — sab kar sakte hain (controller mein employee check hoga)
    Route::patch('/tickets/{id}/status', [TicketController::class, 'updateStatus']);

    // Comments — sab access kar sakte hain
    Route::get('/tickets/{ticketId}/comments', [CommentController::class, 'index']);
    Route::post('/tickets/{ticketId}/comments', [CommentController::class, 'store']);
    Route::put('/tickets/{ticketId}/comments/{commentId}', [CommentController::class, 'update']);
    Route::delete('/tickets/{ticketId}/comments/{commentId}', [CommentController::class, 'destroy']);

    // Attachments — sab access kar sakte hain
    Route::get('/tickets/{id}/attachments', [AttachmentController::class, 'index']);
    Route::post('/tickets/{id}/attachments', [AttachmentController::class, 'store']);
    Route::get('/attachments/{id}/download', [AttachmentController::class, 'download']);
    Route::delete('/tickets/{id}/attachments/{attachmentId}', [AttachmentController::class, 'destroy']);

    // Admin + Manager only
    Route::middleware('role:admin,manager')->group(function () {

        // Dashboard stats
        Route::get('/tickets/stats', [TicketController::class, 'stats']);

        // Tickets — full CRUD
        Route::get('/tickets', [TicketController::class, 'index']);
        Route::post('/tickets', [TicketController::class, 'store']);
        Route::put('/tickets/{id}', [TicketController::class, 'update']);
        Route::delete('/tickets/{id}', [TicketController::class, 'destroy']);

        // Ticket Assignment
        Route::put('/tickets/{id}/assign', [TicketController::class, 'assign']);
        Route::put('/tickets/{id}/unassign', [TicketController::class, 'unassign']);

        // Activity Logs
        Route::get('/tickets/{id}/activity-logs', [ActivityLogController::class, 'index']);
    });

    // Admin only
    Route::middleware('role:admin')->group(function () {

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
    });

});