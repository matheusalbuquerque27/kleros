<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitante extends Model
{
    public function sit_visitante(){
        return $this->belongsTo(SituacaoVisitante::class);
    }
}
