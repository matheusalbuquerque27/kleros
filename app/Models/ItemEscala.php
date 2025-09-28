<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemEscala extends Model
{
    protected $table = 'itens_escala';

    protected $fillable = [
        'escala_id',
        'funcao',
        'membro_id',
        'responsavel_externo',
    ];

    public function escala()
    {
        return $this->belongsTo(Escala::class);
    }

    public function membro()
    {
        return $this->belongsTo(Membro::class);
    }
}
