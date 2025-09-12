<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agrupamento extends Model
{
    public function congregacao() {
        return $this->belongsTo(Congregacao::class, 'congregacao_id');
    }

    public function lider() {
        return $this->belongsTo(Membro::class, 'lider_id');
    }

    public function colider() {
        return $this->belongsTo(Membro::class, 'colider_id');
    }

    public function integrantes(){
        return $this->belongsToMany(Membro::class, 'agrupamentos_membros', 'agrupamento_id', 'membro_id');
    }

}
