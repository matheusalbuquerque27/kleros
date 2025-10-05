<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Membro extends Model
{

    protected $cast = [
        'data_nascimento' => 'date',
        'data_batismo' => 'date',
        'data_conversao' => 'date',
        'data_cadastro' => 'date',
        'ativo' => 'boolean',
    ];

    protected static function booted() {
        static::creating(function ($membro) {
            $membro->congregacao_id = app('congregacao')->id;
        });

        static::updating(function ($membro) {
            $membro->congregacao_id = app('congregacao')->id;
        });
    }

    public function estadoCiv() {
        return $this->belongsTo(EstadoCiv::class);
    }
    public function escolaridade() {
        return $this->belongsTo(Escolaridade::class);
    }
    public function ministerio()
    {
        return $this->belongsTo(Ministerio::class);
    }
    public function celulas(): BelongsToMany
    {
        return $this->belongsToMany(Celula::class, 'membro_celula', 'membro_id', 'celula_id');
    }

    public function denominacao() {
        return $this->belongsTo(Denominacao::class);
    }
    public function congregacao() {
        return $this->belongsTo(Congregacao::class);
    }
    public function setor() {
        return $this->belongsTo(Setor::class);
    }
    public function celula() {
        return $this->belongsToMany(Celula::class, 'membro_celula', 'membro_id', 'celula_id');
    }
    public function agrupamentos() {
        return $this->belongsToMany(Agrupamento::class, 'agrupamentos_membros', 'membro_id', 'agrupamento_id');
    }
    public function eventos()
    {
        return $this->belongsToMany(Evento::class, 'evento_membro', 'membro_id', 'evento_id');
    }
    public function user()
    {
        return $this->hasOne(User::class);
    }
    public function reunioes()
    {
        return $this->belongsToMany(Reuniao::class, 'reuniao_membro', 'membro_id', 'reuniao_id');
    }
    public function avisos()
    {
        return $this->belongsToMany(Aviso::class, 'aviso_membro', 'membro_id', 'aviso_id')
            ->withPivot('lido')
            ->withTimestamps();
    }

    /**
     * Retorna todos os avisos disponíveis para o membro considerando seus públicos.
     */
    public function avisosVisiveis(): Collection
    {
        $this->loadMissing('agrupamentos');

        $congregacao = app('congregacao');

        $avisosParaTodos = Aviso::where('congregacao_id', $congregacao->id)
            ->where('para_todos', true)
            ->get();

        $avisosIndividuais = $this->avisos()
            ->where('congregacao_id', $congregacao->id)
            ->get();

        $grupoIds = $this->agrupamentos->pluck('id')->toArray();

        $avisosPorGrupos = Aviso::where('congregacao_id', $congregacao->id)
            ->whereNotNull('destinatarios_agrupamentos')
            ->get()
            ->filter(function ($aviso) use ($grupoIds) {
                return is_array($aviso->destinatarios_agrupamentos)
                    && count(array_intersect($grupoIds, $aviso->destinatarios_agrupamentos)) > 0;
            });

        return $avisosParaTodos
            ->merge($avisosIndividuais)
            ->merge($avisosPorGrupos)
            ->unique('id')
            ->sortByDesc('created_at')
            ->values();
    }
    /**
     * Scope para filtrar membros pela congregação atual
     */
    public function scopeDaCongregacao($query)
    {
        return $query->where('congregacao_id', app('congregacao')->id);
    }

}
