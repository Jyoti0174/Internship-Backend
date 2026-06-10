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
        $it      = Department::where('name', 'Information Technology')->first()->id;
        $hr      = Department::where('name', 'Human Resources')->first()->id;
        $finance = Department::where('name', 'Finance')->first()->id;

        $users = [
            ['name' => 'Rahul Sharma',  'email' => 'rahul@example.com',  'password' => Hash::make('password'), 'department_id' => $it],
            ['name' => 'Priya Singh',   'email' => 'priya@example.com',  'password' => Hash::make('password'), 'department_id' => $it],
            ['name' => 'Amit Verma',    'email' => 'amit@example.com',   'password' => Hash::make('password'), 'department_id' => $it],
            ['name' => 'Sneha Gupta',   'email' => 'sneha@example.com',  'password' => Hash::make('password'), 'department_id' => $hr],
            ['name' => 'Vikram Yadav',  'email' => 'vikram@example.com', 'password' => Hash::make('password'), 'department_id' => $hr],
            ['name' => 'Neha Patel',    'email' => 'neha@example.com',   'password' => Hash::make('password'), 'department_id' => $hr],
            ['name' => 'Rohit Mishra',  'email' => 'rohit@example.com',  'password' => Hash::make('password'), 'department_id' => $hr],
            ['name' => 'Kavita Joshi',  'email' => 'kavita@example.com', 'password' => Hash::make('password'), 'department_id' => $finance],
            ['name' => 'Suresh Kumar',  'email' => 'suresh@example.com', 'password' => Hash::make('password'), 'department_id' => $finance],
            ['name' => 'Anita Tiwari',  'email' => 'anita@example.com',  'password' => Hash::make('password'), 'department_id' => $finance],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}