<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;

class TasksSeeder extends Seeder
{
    public function run(): void
    {
        Task::create([
            'internship_id' => 1,
            'title' => 'Setup Development Environment',
            'description' => 'Install VSCode, PHP, Composer, and Laravel.',
            'status' => 'pending',
            'due_date' => '2025-01-20'
        ]);
    }
}
