<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UniversitySupervisor;

class UniversitySupervisorSeeder extends Seeder
{
    public function run(): void
    {
        UniversitySupervisor::create([
            'user_id' => 4,
            'department' => 'Faculty of Computing',
            'staff_id'   => 'U123456',
            'phone' => '0188877766',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
