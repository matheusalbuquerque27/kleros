<?php

namespace Modules\Cursos\Models;

use Illuminate\Database\Eloquent\Model;

class Exercicio extends Model
{
    protected $fillable = ['licao_id', 'titulo', 'conteudo', 'ordem', 'ativo'];

    public function licao()
    {
        return $this->belongsTo(Licao::class);
    }
}
