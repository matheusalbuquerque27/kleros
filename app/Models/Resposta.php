<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resposta extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesquisa_id',
        'pergunta_id',
        'membro_id',
        'resposta_texto',
    ];

    public function pesquisa(): BelongsTo
    {
        return $this->belongsTo(Pesquisa::class);
    }

    public function pergunta(): BelongsTo
    {
        return $this->belongsTo(Pergunta::class);
    }

    public function membro(): BelongsTo
    {
        return $this->belongsTo(Membro::class);
    }

    public function opcoes(): HasMany
    {
        return $this->hasMany(RespostaOpcao::class);
    }
}
