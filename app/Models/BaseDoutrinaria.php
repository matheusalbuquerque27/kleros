<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseDoutrinaria extends Model
{
    protected $table = 'bases_doutrinarias';

    public function denominacoes()
    {
        return $this->hasMany(Denominacao::class, 'base_doutrinaria');
    }

}
