<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    /** @use HasFactory<\Database\Factories\InscriptionFactory> */
    use HasFactory;

    protected $fillable = ['course_id', 'student_id', 'token', 'survey_isfilled'];
}
