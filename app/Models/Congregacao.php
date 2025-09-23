<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;     

class Congregacao extends Model
{
    protected $table = 'congregacoes';

    public function config()
    {
        return $this->hasOne(CongregacaoConfig::class, 'congregacao_id');
    }
    public function denominacao()
    {
        return $this->belongsTo(Denominacao::class, 'denominacao_id');
    }
    public function dominio()
    {
        return $this->hasOne(Dominio::class);
    }
    public function membros()
    {
        return $this->hasMany(Membro::class, 'congregacao_id');
    }
    public function celulas()
    {
        return $this->hasMany(Celula::class, 'congregacao_id');
    }
    public function grupos()
    {
        return $this->hasMany(Agrupamento::class, 'congregacao_id');
    }
    public function setores()
    {
        return $this->hasMany(Setor::class, 'congregacao_id');
    }
    public function reunioes()
    {
        return $this->hasMany(Reuniao::class, 'congregacao_id');
    }
    public function visitantes()
    {
        return $this->hasMany(Visitante::class, 'congregacao_id');
    }
    public function cidade(){
        return $this->BelongsTo(Cidade::class, 'cidade_id');
    }
    public function estado(){
        return $this->BelongsTo(Estado::class, 'estado_id');
    }
    public function pais(){
        return $this->BelongsTo(Pais::class, 'pais_id');
    }

}
