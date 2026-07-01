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
    $prefs = $user->notification_preferences ?? [];

    return response()->json([
        'data' => [
            'ticket_created'  => $prefs['ticket_created']  ?? false,
            'ticket_assigned' => $prefs['ticket_assigned'] ?? false,
            'status_changed'  => $prefs['status_changed']  ?? false,
            'comment_added'   => $prefs['comment_added']   ?? false,
        ]
    ]);
}

public function updateNotificationPreferences(Request $request)
{
    $user = $request->user();

    $validated = $request->validate([
        'ticket_created'  => 'boolean',
        'ticket_assigned' => 'boolean',
        'status_changed'  => 'boolean',
        'comment_added'   => 'boolean',
    ]);

    $existing = $user->notification_preferences ?? [];
    $updated = array_merge($existing, $validated);

    $user->notification_preferences = $updated;
    $user->save();

    return response()->json([
        'message' => 'Notification preferences updated successfully.',
        'data'    => $updated
    ]);
}
}