<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('announcements', function (Blueprint $table) {
        $table->string('role')->nullable()->after('for_students'); // e.g. 'student', 'university_sv', 'industry_sv', 'all'
    });
    Schema::table('important_dates', function (Blueprint $table) {
        $table->string('role')->nullable()->after('for_students');
    });
}

public function down()
{
    Schema::table('announcements', function (Blueprint $table) {
        $table->dropColumn('role');
    });
    Schema::table('important_dates', function (Blueprint $table) {
        $table->dropColumn('role');
    });
}
};
