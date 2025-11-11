<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Information Technology',
                'code' => 'IT',
                'description' => 'Responsible for managing and maintaining technology infrastructure, software development, and IT support',
            ],
            [
                'name' => 'Human Resources',
                'code' => 'HR',
                'description' => 'Manages employee recruitment, training, benefits, and employee relations',
            ],
            [
                'name' => 'Finance',
                'code' => 'FIN',
                'description' => 'Handles financial planning, accounting, budgeting, and financial reporting',
            ],
            [
                'name' => 'Marketing',
                'code' => 'MKT',
                'description' => 'Develops and implements marketing strategies, brand management, and customer engagement',
            ],
            [
                'name' => 'Sales',
                'code' => 'SLS',
                'description' => 'Manages sales operations, client relationships, and revenue generation',
            ],
            [
                'name' => 'Operations',
                'code' => 'OPS',
                'description' => 'Oversees daily business operations, process improvement, and operational efficiency',
            ],
            [
                'name' => 'Customer Service',
                'code' => 'CS',
                'description' => 'Provides customer support, handles inquiries, and ensures customer satisfaction',
            ],
            [
                'name' => 'Product Development',
                'code' => 'PD',
                'description' => 'Manages product design, development, and innovation',
            ],
            [
                'name' => 'Quality Assurance',
                'code' => 'QA',
                'description' => 'Ensures product and service quality through testing and quality control processes',
            ],
            [
                'name' => 'Legal',
                'code' => 'LGL',
                'description' => 'Handles legal matters, compliance, and corporate governance',
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
