<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportantDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'date',
        //'for_students',
        'role',
    ];
}