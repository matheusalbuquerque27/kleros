<?php

namespace Modules\Cursos\Models;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $fillable = ['curso_id', 'titulo', 'descricao', 'ordem', 'ativo'];

    public function licoes()
    {
        return $this->hasMany(Licao::class)->orderBy('ordem');
    }

    public function progressoUsuarios()
    {
        return $this->hasMany(ProgressoUsuario::class);
    }

    public function totalLicoes()
    {
        return $this->licoes()->count();
    }

    public function scopeOrdenado($query)
    {
        return $query->orderBy('ordem');
    }
}
