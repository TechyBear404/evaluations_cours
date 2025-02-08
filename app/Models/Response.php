<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $fillable = ['content', 'question_id', 'survey_id', 'option_id', 'student_id'];

    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function option()
    {
        return $this->belongsTo(Options::class);
    }
}
