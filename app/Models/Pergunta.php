<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pergunta extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesquisa_id',
        'texto',
        'tipo',
    ];

    public function pesquisa(): BelongsTo
    {
        return $this->belongsTo(Pesquisa::class);
    }

    public function opcoes(): HasMany
    {
        return $this->hasMany(OpcaoPergunta::class, 'pergunta_id');
    }

    public function respostas(): HasMany
    {
        return $this->hasMany(Resposta::class);
    }
}
