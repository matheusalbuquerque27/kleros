<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cidade extends Model
{
    public function estado() {
        return $this->belongsTo(Estado::class, 'estado_id');
    }
    public function congregacoes() {
        return $this->hasMany(Congregacao::class);
    }
}
