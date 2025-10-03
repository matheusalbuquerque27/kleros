<?php

namespace Modules\Projetos\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjetoLista extends Model
{
    protected $table = 'projetos_listas';

    protected $fillable = [
        'projeto_id',
        'titulo',
    ];

    public function projeto(): BelongsTo
    {
        return $this->belongsTo(Projeto::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(ProjetoCard::class, 'lista_id');
    }
}
