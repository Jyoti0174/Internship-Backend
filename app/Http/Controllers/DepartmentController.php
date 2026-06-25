<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('head')
            ->withCount('users')
            ->get()
            ->map(function ($dept) {
                return [
                    'id'             => $dept->id,
                    'name'           => $dept->name,
                    'description'    => $dept->description,
                    'employee_count' => $dept->users_count,
                    'department_head' => $dept->head ? [
                        'id'   => $dept->head->id,
                        'name' => $dept->head->name,
                    ] : null,
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Departments fetched successfully.',
            'data'    => $departments,
        ], 200);
    }

    public function show($id)
    {
        $dept = Department::with('head')
            ->withCount('users')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Department fetched successfully.',
            'data'    => [
                'id'              => $dept->id,
                'name'            => $dept->name,
                'description'     => $dept->description,
                'employee_count'  => $dept->users_count,
                'department_head' => $dept->head ? [
                    'id'   => $dept->head->id,
                    'name' => $dept->head->name,
                ] : null,
            ],
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|min:3|max:255|unique:departments,name',
            'description' => 'nullable|string|max:500',
            'head_id'     => 'nullable|exists:users,id',
        ]);

        $department = Department::create($request->only(['name', 'description', 'head_id']));

        return response()->json([
            'success' => true,
            'message' => 'Department created successfully.',
            'data'    => $department->load('head'),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $request->validate([
            'name'        => 'sometimes|string|min:3|max:255|unique:departments,name,'.$id,
            'description' => 'nullable|string|max:500',
            'head_id'     => 'nullable|exists:users,id',
        ]);

        $department->update($request->only(['name', 'description', 'head_id']));

        return response()->json([
            'success' => true,
            'message' => 'Department updated successfully.',
            'data'    => $department->load('head'),
        ], 200);
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return response()->json([
            'success' => true,
            'message' => 'Department deleted successfully.',
        ], 200);
    }
}