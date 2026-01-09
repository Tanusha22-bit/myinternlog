<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'internship_id',
        'title',
        'description',
        'status',
        'due_date',
        'student_note',
    ];

public function internship() {
    return $this->belongsTo(\App\Models\Internship::class, 'internship_id');
}

}
