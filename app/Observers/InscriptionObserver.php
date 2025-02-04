<?php

namespace App\Observers;

use App\Models\Inscription;
use Illuminate\Support\Str;

class InscriptionObserver
{
    public function creating(Inscription $inscription)
    {
        $inscription->token = Str::random(32);
    }
}
