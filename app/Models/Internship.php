<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    use HasFactory;

    public function industrySupervisor()
    {
        return $this->belongsTo(\App\Models\IndustrySupervisor::class, 'industry_sv_id');
    }
    public function universitySupervisor()
    {
        return $this->belongsTo(\App\Models\UniversitySupervisor::class, 'university_sv_id');
    }
    public function student() { return $this->belongsTo(Student::class, 'student_id'); }
    
    protected $fillable = [
    'student_id',
    'industry_sv_id',
    'university_sv_id',
    'company_name',
    'company_address',
    'start_date',
    'end_date',
    'status',
    ];

    public function dailyReports()
    {
        return $this->hasMany(\App\Models\DailyReport::class);
    }

    public function tasks() {
        return $this->hasMany(\App\Models\Task::class);
    }
}
