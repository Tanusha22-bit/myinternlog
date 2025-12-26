<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DailyReport;

class DailyReportSeeder extends Seeder
{
    public function run(): void
    {
        DailyReport::create([
            'internship_id' => 1,
            'report_date' => '2025-01-10',
            'task' => 'Completed frontend layout and integrated basic components.',
            'student_notes' => 'Had difficulty with responsive design but fixed it after testing.',

            // University Supervisor feedback
            'uni_feedback' => 'Good progress, make sure to follow the UI guidelines.',
            'uni_feedback_by' => 1, // ID of university_supervisors table
            'uni_feedback_date' => now(),

            // Industry Supervisor feedback
            'industry_feedback' => 'Well done. Please focus on improving accessibility next.',
            'industry_feedback_by' => 1, // ID of industry_supervisors table
            'industry_feedback_date' => now(),

            'status' => 'reviewed',
        ]);
    }
}
