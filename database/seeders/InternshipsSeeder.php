<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Internship;

class InternshipsSeeder extends Seeder
{
    public function run(): void
    {
        Internship::create([
            'student_id' => 1,
            'industry_sv_id' => 1,
            'university_sv_id' => 1,
            'company_name' => 'ABC Tech',
            'company_address' => 'Kuala Lumpur',
            'start_date' => '2025-01-10',
            'end_date' => '2025-04-10',
            'status' => 'active'
        ]);
    }
}
