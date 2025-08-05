<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use App\Models\Departamento;
use App\Models\Setor;
use App\Models\Membro;
use Illuminate\Http\Request;

class ReuniaoController extends Controller
{
    public function create(){

        $grupos = Grupo::all();
        $departamentos = Departamento::all();
        $setores = Setor::all();
        $membros = Membro::all();

        return view('reunioes.cadastro', ['grupos' => $grupos, 'departamentos' => $departamentos, 'setores' => $setores, 'membros' => $membros]);
    }
}
