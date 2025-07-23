<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CongregacaoConfig extends Model
{
    protected $casts = [
        'conjunto_cores' => 'array', 
    ];

    public function congregacao()
    {
        return $this->belongsTo(Congregacao::class, 'congregacao_id');
    }

}
