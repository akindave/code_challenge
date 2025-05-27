<?php

namespace Database\Seeders;

use App\Models\ApprovalLevel;
use Illuminate\Database\Seeder;

class ApprovalLevelSeeder extends Seeder
{
    private array $levels = [
        ['name' => 'First Level', 'level' => 1],
        ['name' => 'Second Level', 'level' => 2]
    ];

    public function run(): void
    {
        foreach ($this->levels as $level) {
            ApprovalLevel::create($level);
        }
    }
}