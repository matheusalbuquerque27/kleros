<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresenteEncontro extends Model
{
    protected $table = 'presentes_encontros';

    protected $fillable = [
        'encontro_id',
        'membro_id',
        'visitante_nome',
        'visitante_telefone',
        'visitante_sit_id',
        'visitante_observacoes',
        'visitante_id',
    ];

    public function encontro()
    {
        return $this->belongsTo(EncontroCelula::class, 'encontro_id');
    }

    public function membro()
    {
        return $this->belongsTo(Membro::class, 'membro_id');
    }

    public function situacaoVisitante()
    {
        return $this->belongsTo(SituacaoVisitante::class, 'visitante_sit_id');
    }

    public function visitante()
    {
        return $this->belongsTo(Visitante::class, 'visitante_id');
    }

    /**
     * Retorna o nome do participante independente do tipo.
     */
    public function getNomeAttribute(): string
    {
        return optional($this->membro)->nome
            ?? $this->visitante_nome
            ?? 'Participante sem identificação';
    }

    /**
     * Indica se a presença é de um visitante (não membro).
     */
    public function isVisitante(): bool
    {
        return is_null($this->membro_id);
    }
}
