<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $fillable = ['content', 'question_id', 'inscription_id'];

    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
