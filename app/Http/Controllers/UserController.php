<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('department')->get();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::with('department')->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'          => 'sometimes|string|max:255',
            'email'         => 'sometimes|email|unique:users,email,'.$id,
            'password'      => 'sometimes|string|min:8',
            'role'          => 'sometimes|in:admin,agent,employee',
            'department_id' => 'sometimes|nullable|exists:departments,id',
        ]);

        if ($request->has('password')) {
            $request->merge(['password' => Hash::make($request->password)]);
        }

        $user->update($request->only([
            'name', 'email', 'password', 'role', 'department_id'
        ]));

        return response()->json([
            'message' => 'User updated successfully',
            'user'    => $user->fresh('department'),
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ]);
    }

    public function tickets($id)
    {
        $user = User::with('tickets')->findOrFail($id);

        return response()->json([
            'user'    => $user->name,
            'tickets' => $user->tickets,
        ]);
    }
}