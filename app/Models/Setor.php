<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Setor extends Agrupamento
{

    protected $table = 'agrupamentos';

    protected static function booted(): void
    {
        static::addGlobalScope('setor', function (Builder $builder) {
            $builder->where('tipo', 'setor');
        });

        static::creating(function (self $model) {
            $model->tipo = 'setor';
        });

        static::updating(function (self $model) {
            $model->tipo = 'setor';
        });
    }

    public function departamentos()
    {
        return $this->departamentosFilhos();
    }

    public function grupos()
    {
        return $this->filhos()->where('tipo', 'grupo');
    }
}
