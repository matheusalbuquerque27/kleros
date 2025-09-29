<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caixa extends Model
{
    protected $fillable = ['nome', 'descricao', 'congregacao_id'];

    public function congregacao()
    {
        return $this->belongsTo(Congregacao::class);
    }

    public function lancamentos()
    {
        return $this->hasMany(LancamentoFinanceiro::class);
    }

    public function saldo(): float
    {
        $entradas = $this->lancamentos()->where('tipo', 'entrada')->sum('valor');
        $saidas = $this->lancamentos()->where('tipo', 'saida')->sum('valor');

        return (float) ($entradas - $saidas);
    }
}
