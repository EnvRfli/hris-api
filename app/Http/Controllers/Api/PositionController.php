<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        $query = Position::with('department')->where('is_active', true);

        // Filter by department_id if provided
        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Filter by level if provided
        if ($request->has('level')) {
            $query->where('level', $request->level);
        }

        $positions = $query->get();
        return response()->json($positions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:positions',
            'department_id' => 'nullable|exists:departments,id',
            'level' => 'nullable|integer|min:1|max:5',
            'min_salary' => 'nullable|numeric|min:0',
            'max_salary' => 'nullable|numeric|min:0',
        ]);

        $position = Position::create($request->all());

        return response()->json([
            'message' => 'Position created successfully',
            'position' => $position->load('department')
        ], 201);
    }

    public function show($id)
    {
        $position = Position::with(['department', 'employees'])->findOrFail($id);
        return response()->json($position);
    }

    public function update(Request $request, $id)
    {
        $position = Position::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|unique:positions,code,' . $id,
        ]);

        $position->update($request->all());

        return response()->json([
            'message' => 'Position updated successfully',
            'position' => $position->load('department')
        ]);
    }

    public function destroy($id)
    {
        $position = Position::findOrFail($id);
        $position->update(['is_active' => false]);

        return response()->json(['message' => 'Position deactivated successfully']);
    }
}

