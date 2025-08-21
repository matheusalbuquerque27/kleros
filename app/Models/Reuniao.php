<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reuniao extends Model
{
    protected $table = 'reunioes';

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
        'privado' => 'boolean'
    ];

    public function congregacao()
    {
        return $this->belongsTo(Congregacao::class);
    }
    public function membros()
    {
        return $this->belongsToMany(Membro::class, 'reuniao_membro', 'reuniao_id', 'membro_id');
    }
    public function grupos()
    {
        return $this->morphMany(ReuniaoGrupo::class, 'reuniao');
    }
}
