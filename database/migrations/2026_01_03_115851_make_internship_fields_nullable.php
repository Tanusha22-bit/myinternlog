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
    Schema::table('internships', function (Blueprint $table) {
        $table->string('company_name')->nullable()->change();
        $table->string('company_address')->nullable()->change();
        $table->date('start_date')->nullable()->change();
        $table->date('end_date')->nullable()->change();
    });
}

public function down()
{
    Schema::table('internships', function (Blueprint $table) {
        $table->string('company_name')->nullable(false)->change();
        $table->string('company_address')->nullable(false)->change();
        $table->date('start_date')->nullable(false)->change();
        $table->date('end_date')->nullable(false)->change();
    });
}
};
