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
            // WAJIB - User data
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            
            // WAJIB - Employee basic data
            'employee_id' => 'required|string|unique:employee_profiles',
            'phone' => 'required|string|max:20',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'employment_status' => 'required|in:permanent,contract,internship,probation',
            'join_date' => 'required|date',
            'role' => 'required|in:employee,manager,hr',
            
            // OPSIONAL - Personal data (bisa diisi employee nanti)
            'nip' => 'nullable|string|unique:employee_profiles,nip',
            'nik' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:100',
            'gender' => 'nullable|in:male,female',
            'religion' => 'nullable|in:islam,kristen,katolik,hindu,buddha,konghucu',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            
            // OPSIONAL - Employment details (HR only)
            'work_shift_id' => 'nullable|exists:work_shifts,id',
            'manager_id' => 'nullable|exists:employee_profiles,id',
            'employment_type' => 'nullable|in:permanent,contract,internship',
            'probation_end_date' => 'nullable|date',
            'permanent_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            
            // OPSIONAL - Salary (HR only)
            'basic_salary' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'tax_number' => 'nullable|string|max:20',
            
            // OPSIONAL - Leave quota
            'annual_leave_quota' => 'nullable|integer|min:0|max:30',
            
            // OPSIONAL - Bank info (bisa diisi employee nanti)
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:255',
            
            // OPSIONAL - Emergency contact (bisa diisi employee nanti)
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:50',
        ]);

        DB::beginTransaction();
        try {
            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Assign role
            $user->assignRole($request->role);

            // Create employee profile dengan semua field yang ada
            EmployeeProfile::create([
                'user_id' => $user->id,
                'employee_id' => $request->employee_id,
                'nip' => $request->nip,
                'nik' => $request->nik,
                'phone' => $request->phone,
                'birth_date' => $request->birth_date,
                'birth_place' => $request->birth_place,
                'gender' => $request->gender,
                'religion' => $request->religion,
                'marital_status' => $request->marital_status,
                'address' => $request->address,
                'city' => $request->city,
                'province' => $request->province,
                'postal_code' => $request->postal_code,
                'department_id' => $request->department_id,
                'position_id' => $request->position_id,
                'work_shift_id' => $request->work_shift_id,
                'manager_id' => $request->manager_id,
                'employment_status' => $request->employment_status,
                'employment_type' => $request->employment_type,
                'join_date' => $request->join_date,
                'probation_end_date' => $request->probation_end_date,
                'permanent_date' => $request->permanent_date,
                'end_date' => $request->end_date,
                'basic_salary' => $request->basic_salary ?? 0,
                'allowances' => $request->allowances ?? 0,
                'tax_number' => $request->tax_number,
                'annual_leave_quota' => $request->annual_leave_quota ?? 12,
                'remaining_leave' => $request->annual_leave_quota ?? 12,
                'bank_name' => $request->bank_name,
                'bank_account_number' => $request->bank_account_number,
                'bank_account_name' => $request->bank_account_name,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'emergency_contact_relation' => $request->emergency_contact_relation,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Employee created successfully',
                'employee' => $user->load([
                    'employeeProfile.department', 
                    'employeeProfile.position', 
                    'employeeProfile.workShift',
                    'employeeProfile.manager',
                    'roles'
                ]),
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

