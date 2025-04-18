<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membro extends Model
{
    public function estadoCiv() {
        return $this->belongsTo(EstadoCiv::class);
    }
    public function escolaridade() {
        return $this->belongsTo(Escolaridade::class);
    }
    public function ministerio() {
        return $this->belongsTo(Ministerio::class);
    }
    public function obreiro(){
        return $this->hasMany(Obreiro::class);
    }
    public function grupo(){
        return $this->hasMany(Grupo::class);
    }
    public function gruposMembro(){
        return $this->belongsToMany(Grupo::class, 'grupo_integrantes');
    }

}
