<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EncontroCelula extends Model
{
    protected $table = 'encontros_celulas';

    public function celula()
    {
        return $this->belongsTo(Celula::class);
    }

    public function congregacao()
    {
        return $this->belongsTo(Congregacao::class);
    }

    public function preletor()
    {
        return $this->belongsTo(Membro::class, 'preletor_id');
    }

    public function presentes()
    {
        return $this->hasMany(PresenteEncontro::class, 'encontro_id');
    }
}
