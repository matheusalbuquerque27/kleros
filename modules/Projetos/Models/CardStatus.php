<?php

namespace Modules\Projetos\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Projetos\Models\Projeto;

class CardStatus extends Model
{
    protected $table = 'cards_status';

    protected $fillable = [
        'projeto_id',
        'nome',
        'ordem',
    ];

    public function cards(): HasMany
    {
        return $this->hasMany(ProjetoCard::class, 'status_id');
    }

    public function projeto(): BelongsTo
    {
        return $this->belongsTo(Projeto::class);
    }
}
