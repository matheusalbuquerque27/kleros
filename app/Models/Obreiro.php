<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obreiro extends Model
{
    public function membro() {
        return $this->belongsTo(Membro::class);
    }

    public function ministerio() {
        return $this->belongsTo(Membro::class);
    }

    
}
