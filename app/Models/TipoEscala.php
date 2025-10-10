<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEscala extends Model
{
    protected $table = 'tipos_escala';

    protected $fillable = [
        'nome',
        'ativo',
        'congregacao_id',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function escalas()
    {
        return $this->hasMany(Escala::class);
    }
}
