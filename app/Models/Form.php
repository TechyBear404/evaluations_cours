<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = ['name', 'is_locked'];

    public function components()
    {
        return $this->belongsToMany(Component::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
