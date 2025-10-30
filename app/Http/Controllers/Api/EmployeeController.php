<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmployeeProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = User::with(['employeeProfile.department', 'employeeProfile.position', 'roles'])
            ->whereHas('employeeProfile')
            ->paginate(15);

        return response()->json($employees);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'employee_id' => 'required|string|unique:employee_profiles',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'employment_status' => 'required|in:permanent,contract,internship,probation',
            'join_date' => 'required|date',
            'role' => 'required|in:employee,manager,hr',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole($request->role);

            EmployeeProfile::create([
                'user_id' => $user->id,
                'employee_id' => $request->employee_id,
                'phone' => $request->phone,
                'department_id' => $request->department_id,
                'position_id' => $request->position_id,
                'work_shift_id' => $request->work_shift_id,
                'employment_status' => $request->employment_status,
                'join_date' => $request->join_date,
                'basic_salary' => $request->basic_salary ?? 0,
                'annual_leave_quota' => $request->annual_leave_quota ?? 12,
                'remaining_leave' => $request->annual_leave_quota ?? 12,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Employee created successfully',
                'employee' => $user->load(['employeeProfile.department', 'employeeProfile.position', 'roles']),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error creating employee', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $employee = User::with(['employeeProfile.department', 'employeeProfile.position', 'roles'])
            ->findOrFail($id);

        return response()->json(['employee' => $employee]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        DB::beginTransaction();
        try {
            if ($request->has('name') || $request->has('email')) {
                $user->update($request->only(['name', 'email']));
            }

            if ($user->employeeProfile) {
                $user->employeeProfile->update($request->except(['name', 'email', 'password']));
            }

            DB::commit();

            return response()->json([
                'message' => 'Employee updated successfully',
                'employee' => $user->load(['employeeProfile.department', 'employeeProfile.position', 'roles']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error updating employee', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->employeeProfile->update(['is_active' => false]);

        return response()->json(['message' => 'Employee deactivated successfully']);
    }
}

