<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Options extends Model
{
    protected $fillable = ['name', 'question_id', 'type', 'order'];

    protected $attributes = [
        'type' => 'default',  // Valeur par défaut pour le type
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
