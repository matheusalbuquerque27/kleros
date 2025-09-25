<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pesquisa extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'criada_por',
        'data_inicio',
        'data_fim',
        'congregacao_id',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    public function criador(): BelongsTo
    {
        return $this->belongsTo(Membro::class, 'criada_por');
    }

    public function perguntas(): HasMany
    {
        return $this->hasMany(Pergunta::class);
    }

    public function respostas(): HasMany
    {
        return $this->hasMany(Resposta::class);
    }

    public function scopeForCongregacao($query, int $congregacaoId)
    {
        return $query->where('congregacao_id', $congregacaoId);
    }
}
