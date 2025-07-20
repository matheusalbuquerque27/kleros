<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    protected $fillable = ['congregacao_id', 'nome'];

    public function congregacao()
    {
        return $this->belongsTo(Congregacao::class);
    }

    public function departamentos()
    {
        return $this->hasMany(Departamento::class);
    }

    public function membros()
    {
        return $this->hasMany(Membro::class);
    }
}
