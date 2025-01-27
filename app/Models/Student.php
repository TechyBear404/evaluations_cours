<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = ['email'];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'inscriptions', 'student_id', 'course_id');
    }
}
