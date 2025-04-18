<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    public function evento(){
        return $this->hasMany(Grupo::class);
    }

    public function grupo(){
        return $this->hasMany(Grupo::class);
    }

    public function membro(){
        return $this->belongsTo(Membro::class);
    }

    public function integrantes(){
        return $this->belongsToMany(Membro::class, 'grupo_integrantes');
    }
}
