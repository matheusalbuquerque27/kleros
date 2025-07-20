<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recado extends Model
{
    public function culto()
    {
        return $this->belongsTo(Culto::class);
    }
}
