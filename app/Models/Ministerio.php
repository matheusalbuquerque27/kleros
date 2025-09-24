<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ministerio extends Model
{
    public function membros()
    {
        return $this->hasMany(Membro::class);
    }
    public function denominacao()
    {
        return $this->belongsTo(Denominacao::class);
    }
}
