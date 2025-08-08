<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $fillable = ['titulo', 'descricao', 'ativo', 'publico', 'icone', 'congregacao_id'];

    public function congregacao()
    {
        return $this->belongsTo(Congregacao::class);
    }
    public function modulos()
    {
        return $this->hasMany(Modulo::class)->orderBy('ordem');
    }
}
