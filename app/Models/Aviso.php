<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aviso extends Model
{
    public function criador() {
        return $this->belongsTo(User::class, 'criado_por');
    }
}
