<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    public function culto()
    {
        return $this->hasMany(Culto::class);
    }
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }
    public function congregacao()
    {
        return $this->belongsTo(Congregacao::class, 'congregacao_id');
    }
    public function inscritos()
    {
        return $this->belongsToMany(Membro::class, 'evento_membro', 'evento_id', 'membro_id');
    }

}
