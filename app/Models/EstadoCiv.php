<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoCiv extends Model
{
    public function membro()
    {
        return $this->hasMany(Membro::class);
    }
}
