<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ministerio extends Model
{
    public function membro(){
        return $this->hasMany(Membro::class);
    }

    public function obreiro(){
        return $this->hasMany(Obreiro::class);
    }
}
