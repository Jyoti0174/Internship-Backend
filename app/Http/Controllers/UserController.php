<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $users = User::with('department')->get();

        return $this->successResponse($users, 'Users fetched successfully.');
    }

    // POST /api/users - Create new user (Admin only)
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'password'      => 'required|string|min:8',
            'role'          => 'nullable|in:admin,agent,employee,manager',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'role'          => $request->role ?? 'employee',
            'department_id' => $request->department_id,
        ]);

        return $this->successResponse(
            $user->load('department'),
            'User created successfully.',
            201
        );
    }

    public function show($id)
    {
        $user = User::with('department')->findOrFail($id);

        return $this->successResponse($user, 'User fetched successfully.');
    }

    // PUT /api/users/{id} - Admin only
    public function update(Request $request, $id)
    {
        $loggedInUser = $request->user();

        // Employee sirf apna profile update kar sakta hai
        if ($loggedInUser->role === 'employee') {
            if ($loggedInUser->id != $id) {
                return $this->errorResponse(
                    'Access denied. You can only update your own profile.',
                    403
                );
            }

            $request->validate([
                'name'     => 'sometimes|string|max:255',
                'email'    => 'sometimes|email|unique:users,email,'.$id,
                'password' => 'sometimes|string|min:8',
            ]);

            $user = User::findOrFail($id);

            if ($request->has('password')) {
                $request->merge(['password' => Hash::make($request->password)]);
            }

            $user->update($request->only(['name', 'email', 'password']));

            return $this->successResponse(
                $user->fresh('department'),
                'Profile updated successfully.'
            );
        }

        // Admin — sab kuch update kar sakta hai
        $user = User::findOrFail($id);

        $request->validate([
            'name'                => 'sometimes|string|max:255',
            'email'               => 'sometimes|email|unique:users,email,'.$id,
            'password'            => 'sometimes|string|min:8',
            'role'                => 'sometimes|in:admin,agent,employee,manager',
            'department_id'       => 'sometimes|nullable|exists:departments,id',
            'email_notifications' => 'sometimes|boolean',
        ]);

        if ($request->has('password')) {
            $request->merge(['password' => Hash::make($request->password)]);
        }

        $user->update($request->only([
            'name', 'email', 'password', 'role', 'department_id', 'email_notifications'
        ]));

        return $this->successResponse(
            $user->fresh('department'),
            'User updated successfully.'
        );
    }

    // PUT /api/profile - Sab roles apna profile update kar sakte hain
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name'                     => 'sometimes|string|max:255',
            'email'                    => 'sometimes|email|unique:users,email,'.$user->id,
            'password'                 => 'sometimes|string|min:8',
            'email_notifications'      => 'sometimes|boolean',
            'notification_preferences' => 'sometimes|array',
        ]);

        if ($request->has('password')) {
            $request->merge(['password' => Hash::make($request->password)]);
        }

        $user->update($request->only([
            'name', 'email', 'password', 'email_notifications', 'notification_preferences'
        ]));

        return $this->successResponse(
            $user->fresh('department'),
            'Profile updated successfully.'
        );
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return $this->successResponse(null, 'User deleted successfully.');
    }

    public function tickets($id)
    {
        $user = User::with('tickets')->findOrFail($id);

        return $this->successResponse([
            'user'    => $user->name,
            'tickets' => $user->tickets,
        ], 'User tickets fetched successfully.');
    }
}