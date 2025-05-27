<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    private array $departments = [
        ['name' => 'Human Resources', 'description' => 'HR Department'],
        ['name' => 'Finance', 'description' => 'Finance and Accounting'],
        ['name' => 'Engineering', 'description' => 'Product Development'],
        ['name' => 'Marketing', 'description' => 'Digital Marketing'],
    ];

    public function run(): void
    {
        foreach ($this->departments as $department) {
            Department::create($department);
        }
    }
}