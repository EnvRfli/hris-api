<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeProfile extends Model
{
    protected $fillable = [
        'user_id', 'employee_id', 'nip', 'nik', 'phone', 'birth_date', 'birth_place', 
        'gender', 'religion', 'marital_status', 'address', 'city', 'province', 'postal_code', 'photo',
        'department_id', 'position_id', 'work_shift_id', 'manager_id', 
        'employment_status', 'employment_type', 'join_date', 'probation_end_date', 
        'permanent_date', 'end_date', 'resign_date',
        'basic_salary', 'allowances', 'tax_number', 'annual_leave_quota', 'remaining_leave',
        'bank_name', 'bank_account_number', 'bank_account_name',
        'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relation',
        'is_active'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'join_date' => 'date',
        'probation_end_date' => 'date',
        'permanent_date' => 'date',
        'end_date' => 'date',
        'resign_date' => 'date',
        'basic_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function workShift()
    {
        return $this->belongsTo(WorkShift::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
