<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CongregacaoConfig extends Model
{
    public function congregacao()
    {
        return $this->belongsTo(Congregacao::class, 'congregacao_id');
    }

}
