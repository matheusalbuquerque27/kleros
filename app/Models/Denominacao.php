<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Denominacao extends Model
{
    protected $table = 'denominacoes';

    public function congregacoes()
    {
        return $this->hasMany(Congregacao::class, 'denominacao_id');
    }
    public function baseDoutrinaria()
    {
        return $this->hasOne(BaseDoutrinaria::class, 'base_doutrinaria');
    }
    public function ministerios()
    {
        return $this->hasMany(Ministerio::class, 'denominacao_id');
    }
    public function users()
    {
        return $this->hasMany(User::class, 'denominacao_id');
    }
}
