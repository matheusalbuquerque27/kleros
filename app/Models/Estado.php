<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    public function cidades(){
        return $this->hasMany(Estado::class);
    }

    public function pais() {
        return $this->belongsTo(Pais::class, 'paises_id');
    }
    
    public function congregacoes() {
        return $this->hasMany(Congregacao::class);
    }

    
}
