<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'password'      => 'required|string|min:8|confirmed',
            'department_id' => 'nullable|exists:departments,id',
             'role' => 'nullable|in:admin,agent,employee,manager',
        ]);

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'department_id' => $request->department_id,
            'role'          => $request->role ?? 'employee',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'User registered successfully',
            'user'         => $user,
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ], 201);
    }

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'Login successful',
            'user'         => $user,
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    // Get current user
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
    // GET /api/user/notification-preferences
public function getNotificationPreferences(Request $request)
{
    $user = $request->user();
    return response()->json([
        'data' => [
            'ticket_created'  => (bool) $user->notify_ticket_created,
            'ticket_assigned' => (bool) $user->notify_ticket_assigned,
            'status_changed'  => (bool) $user->notify_status_changed,
            'comment_added'   => (bool) $user->notify_comment_added,
        ]
    ]);
}

// PUT /api/user/notification-preferences
public function updateNotificationPreferences(Request $request)
{
    $user = $request->user();
    $validated = $request->validate([
        'ticket_created'  => 'boolean',
        'ticket_assigned' => 'boolean',
        'status_changed'  => 'boolean',
        'comment_added'   => 'boolean',
    ]);
    $user->update([
        'notify_ticket_created'  => $validated['ticket_created']  ?? $user->notify_ticket_created,
        'notify_ticket_assigned' => $validated['ticket_assigned'] ?? $user->notify_ticket_assigned,
        'notify_status_changed'  => $validated['status_changed']  ?? $user->notify_status_changed,
        'notify_comment_added'   => $validated['comment_added']   ?? $user->notify_comment_added,
    ]);
    return response()->json([
        'message' => 'Notification preferences updated successfully.',
        'data' => [
            'ticket_created'  => (bool) $user->notify_ticket_created,
            'ticket_assigned' => (bool) $user->notify_ticket_assigned,
            'status_changed'  => (bool) $user->notify_status_changed,
            'comment_added'   => (bool) $user->notify_comment_added,
        ]
    ]);
}
}