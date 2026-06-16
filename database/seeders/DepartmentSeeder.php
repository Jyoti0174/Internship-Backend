<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Information Technology', 'description' => 'IT Support and Infrastructure'],
            ['name' => 'Human Resources',        'description' => 'HR and Recruitment'],
            ['name' => 'Finance',                'description' => 'Accounts and Finance'],
            ['name' => 'Operations',             'description' => 'Day to day Operations Management'],
            ['name' => 'Marketing',              'description' => 'Marketing and Communications'],
            ['name' => 'Legal',                  'description' => 'Legal and Compliance'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }
}