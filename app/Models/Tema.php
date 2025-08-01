<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
    protected $casts = [
         'propriedades' => 'array', 
    ];

    public function congregacoes() {
        return $this->hasMany(CongregacaoConfig::class);
    }
}
