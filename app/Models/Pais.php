<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table = 'paises';

    public function estados(){
        return $this->hasMany(Estado::class);
    }

    public function congregacoes() {
        return $this->hasMany(Congregacao::class);
    }
}
