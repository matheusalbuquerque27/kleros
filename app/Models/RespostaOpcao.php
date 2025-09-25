<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RespostaOpcao extends Model
{
    use HasFactory;

    protected $table = 'resposta_opcao';

    protected $fillable = [
        'resposta_id',
        'opcao_id',
    ];

    public function resposta(): BelongsTo
    {
        return $this->belongsTo(Resposta::class);
    }

    public function opcao(): BelongsTo
    {
        return $this->belongsTo(OpcaoPergunta::class, 'opcao_id');
    }
}
