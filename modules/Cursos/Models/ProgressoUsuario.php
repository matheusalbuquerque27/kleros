<?php

namespace Modules\Cursos\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressoUsuario extends Model
{
    protected $table = 'progresso_usuarios';

    protected $fillable = ['user_id', 'curso_id', 'modulo_id', 'licoes_concluidas'];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }
}
