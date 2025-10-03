<?php

namespace Modules\Projetos\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Projetos\Models\CardStatus;

class ProjetoCard extends Model
{
    protected $table = 'projetos_cards';

    protected $fillable = [
        'lista_id',
        'titulo',
        'descricao',
        'status_id',
        'data_inicio',
        'data_entrega',
        'anexo',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_entrega' => 'date',
    ];

    public function lista(): BelongsTo
    {
        return $this->belongsTo(ProjetoLista::class, 'lista_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(CardStatus::class, 'status_id');
    }
}
