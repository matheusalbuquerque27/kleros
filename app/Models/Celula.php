<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Celula extends Model
{
    public function congregacao()
    {
        return $this->belongsTo(Congregacao::class);
    }
    public function membros()
    {
        return $this->hasMany(Membro::class);
    }    


    //Métodos auxiliares dinâmicos
    public function lider()
    {
        return $this->belongsTo(Membro::class, 'lider_id');
    }
    public function colider()
    {
        return $this->belongsTo(Membro::class, 'colider_id');
    }
}
