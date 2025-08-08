<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    public function licoes()
    {
        return $this->hasMany(Licao::class)->orderBy('ordem');;
    }
    public function progressoUsuarios()
    {
        return $this->hasMany(ProgressoUsuario::class);
    }
     public function totalLicoes()
    {
        return $this->licoes()->count();
    }
    //Escopo para ordenar os módulos por ordem
    public function scopeOrdenado($query)
    {
        return $query->orderBy('ordem');
    }
    
}
