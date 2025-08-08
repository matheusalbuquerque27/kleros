<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Licao extends Model
{
    protected $table = 'licoes';

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }
    public function exercicios()
    {
        return $this->hasMany(Exercicio::class)->orderBy('ordem');
    }
}
