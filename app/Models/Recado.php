<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recado extends Model
{
    protected $casts = [
        'data_recado' => 'date',
    ];

    public function culto()
    {
        return $this->belongsTo(Culto::class);
    }

    public function membro()
    {
        return $this->belongsTo(Membro::class);
    }
}
