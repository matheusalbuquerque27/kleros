<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Reuniao extends Model
{
    protected $table = 'reunioes';

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
        'privado' => 'boolean',
        'online' => 'boolean',
    ];

    protected $fillable = [
        'congregacao_id',
        'assunto',
        'descricao',
        'data_inicio',
        'data_fim',
        'local',
        'tipo',
        'privado',
        'online',
        'link_online',
    ];

    public function congregacao()
    {
        return $this->belongsTo(Congregacao::class);
    }

    public function membros()
    {
        return $this->belongsToMany(Membro::class, 'reuniao_membros', 'reuniao_id', 'membro_id')->withTimestamps();
    }

    public function agrupamentos()
    {
        return $this->belongsToMany(Agrupamento::class, 'reuniao_agrupamentos', 'reuniao_id', 'agrupamento_id')->withTimestamps();
    }
}
