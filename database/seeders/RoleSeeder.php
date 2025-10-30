<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Admin',
                'description' => 'Full access to all features including system configuration',
            ],
            [
                'name' => 'hr',
                'display_name' => 'Human Resources',
                'description' => 'Manage employees, leaves, reimbursements, and HR operations',
            ],
            [
                'name' => 'manager',
                'display_name' => 'Manager',
                'description' => 'Approve leaves and reimbursements for team members',
            ],
            [
                'name' => 'employee',
                'display_name' => 'Employee',
                'description' => 'Basic employee access for attendance, leaves, and reimbursements',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
