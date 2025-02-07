<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Teacher extends Model
{
    use HasFactory;
    use Notifiable;
    protected $fillable = ['lastname', 'email', 'firstname'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
