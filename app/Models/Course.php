<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'teacher_id', 'form_id', 'start_date', 'end_date', 'year_id'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'inscriptions', 'course_id', 'student_id')
            ->withTimestamps();
    }
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function year()
    {
        return $this->belongsTo(Year::class);
    }
}
