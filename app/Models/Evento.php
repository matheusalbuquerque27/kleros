<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    public function culto()
    {
        return $this->hasMany(Culto::class);
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

}
