<?php

namespace Modules\Projetos\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Projetos\Models\ProjetoLista;
use Modules\Projetos\Models\CardStatus;
use App\Models\Congregacao;
use App\Models\Membro;
use App\Models\Agrupamento;

class Projeto extends Model
{
    protected $table = 'projetos';

    protected $fillable = [
        'congregacao_id',
        'nome',
        'cor',
        'para_todos',
    ];

    protected $casts = [
        'para_todos' => 'boolean',
    ];

    public function congregacao(): BelongsTo
    {
        return $this->belongsTo(Congregacao::class);
    }

    public function listas(): HasMany
    {
        return $this->hasMany(ProjetoLista::class);
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(CardStatus::class, 'projeto_id');
    }

    public function membros(): BelongsToMany
    {
        return $this->belongsToMany(Membro::class, 'projetos_membros');
    }

    public function agrupamentos(): BelongsToMany
    {
        return $this->belongsToMany(Agrupamento::class, 'projetos_agrupamentos');
    }
}
