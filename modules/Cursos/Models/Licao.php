<?php

namespace Modules\Cursos\Models;

use Illuminate\Database\Eloquent\Model;

class Licao extends Model
{
    protected $table = 'licoes';

    protected $fillable = ['modulo_id', 'titulo', 'conteudo', 'ordem', 'ativo'];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

    public function exercicios()
    {
        return $this->hasMany(Exercicio::class)->orderBy('ordem');
    }
}
