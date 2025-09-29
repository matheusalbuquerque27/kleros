<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoContribuicao extends Model
{
    protected $table = 'tipos_contribuicoes';

    protected $fillable = ['nome', 'descricao', 'congregacao_id'];

    public function congregacao()
    {
        return $this->belongsTo(Congregacao::class);
    }

    public function lancamentos()
    {
        return $this->hasMany(LancamentoFinanceiro::class);
    }
}
