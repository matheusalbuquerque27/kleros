<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoLancamento extends Model
{
    protected $table = 'tipos_lancamento';

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
