<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LancamentoFinanceiro extends Model
{

    protected $table = 'lancamentos_financeiros';

    protected $fillable = [
        'caixa_id',
        'tipo_contribuicao_id',
        'tipo',
        'valor',
        'descricao',
        'data_lancamento',
    ];

    protected $casts = [
        'data_lancamento' => 'date',
        'valor' => 'decimal:2',
    ];

    public function caixa()
    {
        return $this->belongsTo(Caixa::class);
    }

    public function tipoContribuicao()
    {
        return $this->belongsTo(TipoContribuicao::class);
    }
}
