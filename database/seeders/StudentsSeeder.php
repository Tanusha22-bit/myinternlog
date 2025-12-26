<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Student::create([
            'user_id' => 2,
            'student_id' => 'S001',
            'program' => 'Software Engineering',
            'phone' => '0123456789',
            'semester' => 'Semester 1',
        ]);
    }
}
