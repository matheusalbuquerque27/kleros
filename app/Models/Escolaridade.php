<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escolaridade extends Model
{
    public function membro()
    {
        return $this->hasMany(Membro::class);
    }

}
