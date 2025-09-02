<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aviso extends Model
{
    protected $fillable = [
        'congregacao_id',
        'titulo',
        'mensagem',
        'para_todos',
        'destinatarios_agrupamentos',
        'data_inicio',
        'data_fim',
        'status',
        'prioridade',
        'criado_por',
    ];

    protected $casts = [
        'para_todos' => 'boolean',
        'destinatarios_agrupamentos' => 'array',
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
    ];

    public function criador()
    {
        return $this->belongsTo(User::class, 'criado_por');
    }
}
