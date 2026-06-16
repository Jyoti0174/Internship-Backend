<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Department;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $it        = Department::where('name', 'Information Technology')->first()->id;
        $hr        = Department::where('name', 'Human Resources')->first()->id;
        $finance   = Department::where('name', 'Finance')->first()->id;
        $ops       = Department::where('name', 'Operations')->first()->id;
        $marketing = Department::where('name', 'Marketing')->first()->id;
        $legal     = Department::where('name', 'Legal')->first()->id;

        $users = [
            // Admins
            ['name' => 'Super Admin',      'email' => 'admin@helpdesk.com',   'password' => Hash::make('password'), 'role' => 'admin',    'department_id' => $it],
            ['name' => 'HR Admin',         'email' => 'hradmin@helpdesk.com', 'password' => Hash::make('password'), 'role' => 'admin',    'department_id' => $hr],

            // IT Department
            ['name' => 'Rahul Sharma',     'email' => 'rahul@example.com',    'password' => Hash::make('password'), 'role' => 'employee', 'department_id' => $it],
            ['name' => 'Priya Singh',      'email' => 'priya@example.com',    'password' => Hash::make('password'), 'role' => 'employee', 'department_id' => $it],
            ['name' => 'Amit Verma',       'email' => 'amit@example.com',     'password' => Hash::make('password'), 'role' => 'employee', 'department_id' => $it],

            // HR Department
            ['name' => 'Sneha Gupta',      'email' => 'sneha@example.com',    'password' => Hash::make('password'), 'role' => 'employee', 'department_id' => $hr],
            ['name' => 'Vikram Yadav',     'email' => 'vikram@example.com',   'password' => Hash::make('password'), 'role' => 'employee', 'department_id' => $hr],
            ['name' => 'Neha Patel',       'email' => 'neha@example.com',     'password' => Hash::make('password'), 'role' => 'employee', 'department_id' => $hr],

            // Finance Department
            ['name' => 'Rohit Mishra',     'email' => 'rohit@example.com',    'password' => Hash::make('password'), 'role' => 'employee', 'department_id' => $finance],
            ['name' => 'Kavita Joshi',     'email' => 'kavita@example.com',   'password' => Hash::make('password'), 'role' => 'employee', 'department_id' => $finance],
            ['name' => 'Suresh Kumar',     'email' => 'suresh@example.com',   'password' => Hash::make('password'), 'role' => 'employee', 'department_id' => $finance],

            // Operations Department
            ['name' => 'Anita Tiwari',     'email' => 'anita@example.com',    'password' => Hash::make('password'), 'role' => 'employee', 'department_id' => $ops],
            ['name' => 'Deepak Raj',       'email' => 'deepak@example.com',   'password' => Hash::make('password'), 'role' => 'employee', 'department_id' => $ops],

            // Marketing Department
            ['name' => 'Pooja Mehta',      'email' => 'pooja@example.com',    'password' => Hash::make('password'), 'role' => 'employee', 'department_id' => $marketing],
            ['name' => 'Arjun Nair',       'email' => 'arjun@example.com',    'password' => Hash::make('password'), 'role' => 'employee', 'department_id' => $marketing],

            // Legal Department
            ['name' => 'Sunita Rao',       'email' => 'sunita@example.com',   'password' => Hash::make('password'), 'role' => 'employee', 'department_id' => $legal],
            ['name' => 'Manish Dubey',     'email' => 'manish@example.com',   'password' => Hash::make('password'), 'role' => 'employee', 'department_id' => $legal],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}