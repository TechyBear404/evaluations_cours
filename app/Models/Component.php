<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type'];

    public function form()
    {
        return $this->belongsToMany(Form::class);
    }
}