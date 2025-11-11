<?php

namespace Database\Seeders;

use App\Models\Position;
use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get department IDs
        $it = Department::where('code', 'IT')->first();
        $hr = Department::where('code', 'HR')->first();
        $finance = Department::where('code', 'FIN')->first();
        $marketing = Department::where('code', 'MKT')->first();
        $sales = Department::where('code', 'SLS')->first();
        $ops = Department::where('code', 'OPS')->first();

        $positions = [
            // IT Department
            [
                'name' => 'Software Engineer',
                'code' => 'SE',
                'department_id' => $it->id,
                'level' => 2, // Mid-level
                'description' => 'Develops and maintains software applications',
                'min_salary' => 8000000,
                'max_salary' => 15000000,
            ],
            [
                'name' => 'Senior Software Engineer',
                'code' => 'SSE',
                'department_id' => $it->id,
                'level' => 3, // Senior
                'description' => 'Senior developer with extensive experience',
                'min_salary' => 15000000,
                'max_salary' => 25000000,
            ],
            [
                'name' => 'IT Manager',
                'code' => 'ITM',
                'department_id' => $it->id,
                'level' => 5, // Manager
                'description' => 'Manages IT team and technology initiatives',
                'min_salary' => 20000000,
                'max_salary' => 35000000,
            ],
            [
                'name' => 'DevOps Engineer',
                'code' => 'DEVOPS',
                'department_id' => $it->id,
                'level' => 2, // Mid-level
                'description' => 'Manages infrastructure and deployment pipelines',
                'min_salary' => 10000000,
                'max_salary' => 18000000,
            ],
            [
                'name' => 'UI/UX Designer',
                'code' => 'UIUX',
                'department_id' => $it->id,
                'level' => 2, // Mid-level
                'description' => 'Designs user interfaces and user experiences',
                'min_salary' => 7000000,
                'max_salary' => 14000000,
            ],

            // HR Department
            [
                'name' => 'HR Specialist',
                'code' => 'HRS',
                'department_id' => $hr->id,
                'level' => 2, // Mid-level
                'description' => 'Handles recruitment, employee relations, and HR administration',
                'min_salary' => 6000000,
                'max_salary' => 12000000,
            ],
            [
                'name' => 'HR Manager',
                'code' => 'HRM',
                'department_id' => $hr->id,
                'level' => 5, // Manager
                'description' => 'Manages HR team and strategic HR initiatives',
                'min_salary' => 15000000,
                'max_salary' => 25000000,
            ],
            [
                'name' => 'Recruitment Officer',
                'code' => 'RO',
                'department_id' => $hr->id,
                'level' => 1, // Junior
                'description' => 'Manages recruitment process and candidate sourcing',
                'min_salary' => 5000000,
                'max_salary' => 10000000,
            ],

            // Finance Department
            [
                'name' => 'Accountant',
                'code' => 'ACC',
                'department_id' => $finance->id,
                'level' => 2, // Mid-level
                'description' => 'Handles accounting and financial reporting',
                'min_salary' => 6000000,
                'max_salary' => 12000000,
            ],
            [
                'name' => 'Finance Manager',
                'code' => 'FM',
                'department_id' => $finance->id,
                'level' => 5, // Manager
                'description' => 'Manages financial planning and analysis',
                'min_salary' => 18000000,
                'max_salary' => 30000000,
            ],
            [
                'name' => 'Financial Analyst',
                'code' => 'FA',
                'department_id' => $finance->id,
                'level' => 2, // Mid-level
                'description' => 'Analyzes financial data and provides insights',
                'min_salary' => 7000000,
                'max_salary' => 13000000,
            ],

            // Marketing Department
            [
                'name' => 'Marketing Specialist',
                'code' => 'MS',
                'department_id' => $marketing->id,
                'level' => 2, // Mid-level
                'description' => 'Develops and executes marketing campaigns',
                'min_salary' => 6000000,
                'max_salary' => 12000000,
            ],
            [
                'name' => 'Marketing Manager',
                'code' => 'MM',
                'department_id' => $marketing->id,
                'level' => 5, // Manager
                'description' => 'Manages marketing team and strategy',
                'min_salary' => 16000000,
                'max_salary' => 28000000,
            ],
            [
                'name' => 'Content Creator',
                'code' => 'CC',
                'department_id' => $marketing->id,
                'level' => 1, // Junior
                'description' => 'Creates engaging content for marketing channels',
                'min_salary' => 5000000,
                'max_salary' => 10000000,
            ],
            [
                'name' => 'Social Media Specialist',
                'code' => 'SMS',
                'department_id' => $marketing->id,
                'level' => 1, // Junior
                'description' => 'Manages social media presence and engagement',
                'min_salary' => 5000000,
                'max_salary' => 10000000,
            ],

            // Sales Department
            [
                'name' => 'Sales Executive',
                'code' => 'SX',
                'department_id' => $sales->id,
                'level' => 1, // Junior
                'description' => 'Handles sales operations and client relationships',
                'min_salary' => 5000000,
                'max_salary' => 10000000,
            ],
            [
                'name' => 'Sales Manager',
                'code' => 'SM',
                'department_id' => $sales->id,
                'level' => 5, // Manager
                'description' => 'Manages sales team and revenue targets',
                'min_salary' => 15000000,
                'max_salary' => 25000000,
            ],
            [
                'name' => 'Business Development',
                'code' => 'BD',
                'department_id' => $sales->id,
                'level' => 2, // Mid-level
                'description' => 'Identifies new business opportunities',
                'min_salary' => 7000000,
                'max_salary' => 14000000,
            ],

            // Operations Department
            [
                'name' => 'Operations Officer',
                'code' => 'OO',
                'department_id' => $ops->id,
                'level' => 1, // Junior
                'description' => 'Manages daily operational activities',
                'min_salary' => 5000000,
                'max_salary' => 10000000,
            ],
            [
                'name' => 'Operations Manager',
                'code' => 'OM',
                'department_id' => $ops->id,
                'level' => 5, // Manager
                'description' => 'Oversees operations and process improvements',
                'min_salary' => 16000000,
                'max_salary' => 28000000,
            ],
        ];

        foreach ($positions as $position) {
            Position::create($position);
        }
    }
}
