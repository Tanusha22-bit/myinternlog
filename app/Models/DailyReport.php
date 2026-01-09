<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'internship_id',
        'report_date',
        'task',
        'student_notes',
        'status',
    ];

    public function internship()
    {
        return $this->belongsTo(\App\Models\Internship::class, 'internship_id');
    }
}
