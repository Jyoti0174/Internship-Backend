<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    // GET /api/departments
    public function index()
    {
        $departments = Department::all();

        return response()->json([
            'success' => true,
            'message' => 'Departments fetched successfully.',
            'data'    => $departments,
        ], 200);
    }

    // POST /api/departments
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|min:3|max:255|unique:departments,name',
            'description' => 'nullable|string|max:500',
        ]);

        $department = Department::create($request->only(['name', 'description']));

        return response()->json([
            'success' => true,
            'message' => 'Department created successfully.',
            'data'    => $department,
        ], 201);
    }

    // GET /api/departments/{id}
    public function show($id)
    {
        $department = Department::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Department fetched successfully.',
            'data'    => $department,
        ], 200);
    }

    // PUT /api/departments/{id}
    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $request->validate([
            'name'        => 'sometimes|string|min:3|max:255|unique:departments,name,' . $id,
            'description' => 'nullable|string|max:500',
        ]);

        $department->update($request->only(['name', 'description']));

        return response()->json([
            'success' => true,
            'message' => 'Department updated successfully.',
            'data'    => $department,
        ], 200);
    }

    // DELETE /api/departments/{id}
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