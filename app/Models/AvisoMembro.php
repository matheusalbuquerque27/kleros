<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvisoMembro extends Model
{
    protected $table = 'aviso_membro';

    protected $fillable = [
        'aviso_id',
        'membro_id',
        'lido',
    ];
}