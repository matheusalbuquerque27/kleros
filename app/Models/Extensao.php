<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extensao extends Model
{
    protected $table = 'extensoes';

    protected $fillable = [
        'congregacao_id',
        'module',
        'enabled',
        'options',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'options' => 'array',
    ];

    public function scopeForCongregacao($query, ?int $congregacaoId)
    {
        return $query->where('congregacao_id', $congregacaoId);
    }
}
