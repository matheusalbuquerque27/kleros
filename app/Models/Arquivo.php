<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arquivo extends Model
{
    protected $fillable = [
        'congregacao_id',
        'nome',
        'tipo',
        'caminho',
    ];

    public function congregacao()
    {
        return $this->belongsTo(Congregacao::class, 'congregacao_id');
    }
}
