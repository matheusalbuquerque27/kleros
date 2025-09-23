<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Celula extends Model
{
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    protected static function booted() {
        static::creating(function ($celula) {
            $celula->congregacao_id = app('congregacao')->id;
        });

        static::updating(function ($celula) {
            $celula->congregacao_id = app('congregacao')->id;
        });
    }

    public function congregacao()
    {
        return $this->belongsTo(Congregacao::class);
    }
    public function participantes(): BelongsToMany
    {
        return $this->belongsToMany(Membro::class, 'membro_celula', 'celula_id', 'membro_id');
    }

    public function membros(): BelongsToMany
    {
        return $this->participantes();
    }


    //Métodos auxiliares dinâmicos
    public function lider()
    {
        return $this->belongsTo(Membro::class, 'lider_id');
    }
    public function colider()
    {
        return $this->belongsTo(Membro::class, 'colider_id');
    }
    public function anfitriao()
    {
        return $this->belongsTo(Membro::class, 'anfitriao_id');
    }
}
