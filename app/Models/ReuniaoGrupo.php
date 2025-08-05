<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReuniaoGrupo extends Model
{
    public function notificavel()
    {
        return $this->morphTo();
    }
}
