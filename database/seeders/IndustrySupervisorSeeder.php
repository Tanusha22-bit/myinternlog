<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndustrySupervisorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('industry_supervisors')->insert([
            [
                'user_id' => 3,
                'position' => 'Manager',
                'company' => 'ABC Tech',
                'phone' => '0112233445',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
