<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('internship_id')
                ->constrained('internships')
                ->cascadeOnDelete();

            $table->date('report_date');
            $table->text('task');
            $table->text('student_notes')->nullable();

            // University Supervisor Feedback
            $table->text('uni_feedback')->nullable();
            $table->foreignId('uni_feedback_by')
                ->nullable()
                ->constrained('university_supervisors')
                ->nullOnDelete();
            $table->timestamp('uni_feedback_date')->nullable();

            // Industry Supervisor Feedback
            $table->text('industry_feedback')->nullable();
            $table->foreignId('industry_feedback_by')
                ->nullable()
                ->constrained('industry_supervisors')
                ->nullOnDelete();
            $table->timestamp('industry_feedback_date')->nullable();

            $table->enum('status', ['submitted', 'reviewed'])->default('submitted');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
