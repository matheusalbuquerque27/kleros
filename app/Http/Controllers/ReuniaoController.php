<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use App\Models\Departamento;
use App\Models\Setor;
use App\Models\Membro;
use App\Models\Reuniao;
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

    public function store(Request $request){

        $reuniao = new Reuniao;

        $reuniao->congregacao_id = app('congregacao')->id;
        $reuniao->tipo = $request->tipo_reuniao;
        $reuniao->titulo = $request->titulo ?? 'Reunião';
        $reuniao->data_inicio = $request->data_inicio . ' ' . $request->horario_inicio;
        $reuniao->data_fim = $request->data_fim ?? null;
        $reuniao->local = $request->local;
        $reuniao->descricao = $request->descricao;
        $reuniao->privado = $request->tipo_acesso;
    

        if($request->membros){
            $reuniao->membros()->sync($request->membros);
        }

        $reuniao->save();

        return redirect('/cadastros#reunioes')->with('msg', 'Reunião agendada com sucesso.');
    }

    public function form_criar(){
        $grupos = Grupo::all();
        $departamentos = Departamento::all();
        $setores = Setor::all();
        $membros = Membro::all();

        return view('reunioes.includes.form_criar', ['grupos' => $grupos, 'departamentos' => $departamentos, 'setores' => $setores, 'membros' => $membros]);
    }
}
