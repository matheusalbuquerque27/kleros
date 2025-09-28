<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escala extends Model
{
    protected $fillable = [
        'culto_id',
        'tipo_escala_id',
        'data_hora',
        'local',
        'observacoes',
    ];

    protected $casts = [
        'data_hora' => 'datetime',
    ];

    public function culto()
    {
        return $this->belongsTo(Culto::class);
    }

    public function tipo()
    {
        return $this->belongsTo(TipoEscala::class, 'tipo_escala_id');
    }

    public function itens()
    {
        return $this->hasMany(ItemEscala::class);
    }
}
