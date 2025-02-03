<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = ['name', 'description', 'is_locked'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
