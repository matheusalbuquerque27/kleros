<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Culto extends Model
{
    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }
}
