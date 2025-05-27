<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Department Heads (Approvers)
        $departments = Department::all();
        
        foreach ($departments as $department) {
            $head = User::create([
                'name' => "{$department->name} Head",
                'email' => strtolower(str_replace(' ', '', $department->name)).'@example.com',
                'password' => Hash::make('password'),
                'role' => 'approver',
                'department_id' => $department->id,
            ]);
        }

        // Regular Users
        User::factory()
            ->count(20)
            ->create([
                'role' => 'user',
                'department_id' => fn() => Department::inRandomOrder()->first()->id,
            ]);
    }
}