<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $fillable = ['content', 'question_id', 'course_id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
