<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    public function evento(){
        return $this->hasMany(Evento::class);
    }

    public function membro(){
        return $this->belongsTo(Membro::class);
    }

    public function integrantes(){
        return $this->belongsToMany(Membro::class, 'grupo_integrantes');
    }
}
