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

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Profile
    Route::get('/profile', [AuthController::class, 'me']);
    Route::put('/profile', [UserController::class, 'updateProfile']);

    // Notification Preferences
    Route::get('/user/notification-preferences', [AuthController::class, 'getNotificationPreferences']);
    Route::put('/user/notification-preferences', [AuthController::class, 'updateNotificationPreferences']);

    // Sab roles — apne assigned tickets
    Route::get('/tickets/assigned-to-me', [TicketController::class, 'assignedToMe']);

    // Status update
    Route::patch('/tickets/{id}/status', [TicketController::class, 'updateStatus']);

    // Comments
    Route::get('/tickets/{ticketId}/comments', [CommentController::class, 'index']);
    Route::post('/tickets/{ticketId}/comments', [CommentController::class, 'store']);
    Route::put('/tickets/{ticketId}/comments/{commentId}', [CommentController::class, 'update']);
    Route::delete('/tickets/{ticketId}/comments/{commentId}', [CommentController::class, 'destroy']);

    // Attachments
    Route::get('/tickets/{id}/attachments', [AttachmentController::class, 'index']);
    Route::post('/tickets/{id}/attachments', [AttachmentController::class, 'store']);
    Route::get('/attachments/{id}/download', [AttachmentController::class, 'download']);
    Route::delete('/tickets/{id}/attachments/{attachmentId}', [AttachmentController::class, 'destroy']);

    // Admin + Manager only
    Route::middleware('role:admin,manager')->group(function () {

        // Dashboard stats — specific routes PEHLE {id} se
        Route::get('/tickets/stats', [TicketController::class, 'stats']);
        Route::get('/tickets/stats/by-department', [TicketController::class, 'statsByDepartment']);
        Route::get('/tickets/recent', [TicketController::class, 'recentTickets']);

        // Departments list — dropdown ke liye (admin + manager dono)
        Route::get('/departments', [DepartmentController::class, 'index']);

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

    // Single ticket view — BAAD MEIN (admin+manager group ke baad)
    Route::get('/tickets/{id}', [TicketController::class, 'show']);

    // Admin only
    Route::middleware('role:admin')->group(function () {

        // Users
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
        Route::get('/users/{id}/tickets', [UserController::class, 'tickets']);

        // Departments (create/update/delete — admin only)
        Route::post('/departments', [DepartmentController::class, 'store']);
        Route::get('/departments/{id}', [DepartmentController::class, 'show']);
        Route::put('/departments/{id}', [DepartmentController::class, 'update']);
        Route::delete('/departments/{id}', [DepartmentController::class, 'destroy']);
    });

});