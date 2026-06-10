<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        Department::create(['name' => 'Information Technology', 'description' => 'IT Support and Infrastructure']);
        Department::create(['name' => 'Human Resources', 'description' => 'HR and Recruitment']);
        Department::create(['name' => 'Finance', 'description' => 'Accounts and Finance']);
    }
}