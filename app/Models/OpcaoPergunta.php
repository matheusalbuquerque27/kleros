<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OpcaoPergunta extends Model
{
    use HasFactory;

    protected $table = 'opcoes_pergunta';

    protected $fillable = [
        'pergunta_id',
        'texto',
    ];

    public function pergunta(): BelongsTo
    {
        return $this->belongsTo(Pergunta::class);
    }

    public function respostas(): HasMany
    {
        return $this->hasMany(RespostaOpcao::class, 'opcao_id');
    }
}
