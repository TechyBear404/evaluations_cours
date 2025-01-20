<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $fillable = ['name', 'type'];

    public function form()
    {
        return $this->belongsToMany(Form::class);
    }
}
