<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['label', 'order', 'form_id', 'component_id'];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function component()
    {
        return $this->belongsTo(Component::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function options()
    {
        return $this->hasMany(Options::class);
    }
}
