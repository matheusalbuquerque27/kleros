<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitante extends Model
{
    public function sit_visitante(){
        return $this->belongsTo(SituacaoVisitante::class);
    }
    public function congregacao()
    {
        return $this->belongsTo(Congregacao::class, 'congregacao_id');
    }
    public function culto()
    {
        return $this->morphTo();
    }
}
