<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    public function congregacao()
    {
        return $this->belongsTo(Congregacao::class);
    }
    public function setor()
    {
        return $this->belongsTo(Setor::class);
    }
    public function membros()
    {
        return $this->hasMany(Membro::class);
    }
}
