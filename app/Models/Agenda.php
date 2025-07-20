<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    public function denominacao()
    {
        return $this->belongsTo(Denominacao::class);
    }
    public function congregacao()
    {
        return $this->belongsTo(Congregacao::class);
    }
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }
    public function referencia()
    {
        return $this->morphTo();
    }
    
}
