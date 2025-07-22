<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dominio extends Model
{
    public function congregacao()
    {
        return $this->belongsTo(Congregacao::class);
    }
}
