<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Enums\EmploymentStatus;

class OptionsController extends Controller
{
    /**
     * Get employment status options
     */
    public function employmentStatuses()
    {
        return response()->json(EmploymentStatus::options());
    }

    /**
     * Get all form options at once
     */
    public function index()
    {
        return response()->json([
            'employment_statuses' => [
                ['value' => 'permanent', 'label' => 'Permanent Employee'],
                ['value' => 'contract', 'label' => 'Contract Employee'],
                ['value' => 'internship', 'label' => 'Internship'],
                ['value' => 'probation', 'label' => 'Probation Period'],
            ],
            'employment_types' => [
                ['value' => 'permanent', 'label' => 'Permanent'],
                ['value' => 'contract', 'label' => 'Contract'],
                ['value' => 'internship', 'label' => 'Internship'],
            ],
            'genders' => [
                ['value' => 'male', 'label' => 'Male'],
                ['value' => 'female', 'label' => 'Female'],
            ],
            'marital_statuses' => [
                ['value' => 'single', 'label' => 'Single'],
                ['value' => 'married', 'label' => 'Married'],
                ['value' => 'divorced', 'label' => 'Divorced'],
                ['value' => 'widowed', 'label' => 'Widowed'],
            ],
            'religions' => [
                ['value' => 'islam', 'label' => 'Islam'],
                ['value' => 'kristen', 'label' => 'Kristen'],
                ['value' => 'katolik', 'label' => 'Katolik'],
                ['value' => 'hindu', 'label' => 'Hindu'],
                ['value' => 'buddha', 'label' => 'Buddha'],
                ['value' => 'konghucu', 'label' => 'Konghucu'],
            ],
            'roles' => [
                ['value' => 'employee', 'label' => 'Employee'],
                ['value' => 'manager', 'label' => 'Manager'],
                ['value' => 'hr', 'label' => 'Human Resources'],
            ],
            'levels' => [
                ['value' => 1, 'label' => 'Junior'],
                ['value' => 2, 'label' => 'Mid-level'],
                ['value' => 3, 'label' => 'Senior'],
                ['value' => 4, 'label' => 'Lead'],
                ['value' => 5, 'label' => 'Manager'],
            ],
        ]);
    }
}
