<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = ['course_id', 'is_sent', 'start_date', 'end_date'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }
}
