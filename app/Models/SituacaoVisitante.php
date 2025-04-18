<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SituacaoVisitante extends Model
{
    public function visitante(){
        return $this->hasMany(Visitante::class);
    }
}
