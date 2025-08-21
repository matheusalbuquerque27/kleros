<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Agrupamento;
use App\Models\Membro;
use App\Models\Reuniao;
use Illuminate\Http\Request;

class ReuniaoController extends Controller
{
    public function index() {
        $reunioes = Reuniao::where('congregacao_id', app('congregacao')->id)->paginate(10);
        $congregacao = app('congregacao');
        return view('reunioes.painel', ['congregacao' => $congregacao ,'reunioes' => $reunioes]);
    }

    public function create(){

        $grupos = Agrupamento::where('tipo', 'grupo')->get();
        $departamentos = Agrupamento::where('tipo', 'departamento')->get();
        $setores = Agrupamento::where('tipo', 'setor')->get();
        $congregacao = app('congregacao');
        $membros = Membro::all();

        return view('reunioes.cadastro', ['grupos' => $grupos, 'departamentos' => $departamentos, 'setores' => $setores, 'membros' => $membros, 'congregacao' => $congregacao]);
    }

    public function store(Request $request){

        $reuniao = new Reuniao;

        $reuniao->congregacao_id = app('congregacao')->id;
        $reuniao->tipo = $request->tipo_reuniao;
        $reuniao->assunto = $request->assunto ?? 'Reunião';
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


    public function update(Request $request, Reuniao $reuniao) // se usar Route Model Binding
    {
        // Atualiza campos
        $reuniao->tipo        = $request->tipo;
        $reuniao->assunto     = $request->assunto;
        $reuniao->data_inicio = $request->data_inicio . ' ' . $request->horario_inicio;
        $reuniao->data_fim    = $request->data_fim ?? null;
        $reuniao->local       = $request->local;
        $reuniao->descricao   = $request->descricao;
        $reuniao->privado     = $request->privado;

        $reuniao->save();

        // Se o formulário enviar o campo membros, sincroniza; se não enviar, mantém como está
        if ($request->has('membros')) {
            $reuniao->membros()->sync($validated['membros'] ?? []);
        }

        return redirect('/cadastros#reunioes')->with('msg', 'Reunião atualizada com sucesso.');
    }

    public function form_criar(){
        $grupos = Agrupamento::where('tipo', 'grupo')->get();
        $departamentos = Agrupamento::where('tipo', 'departamento')->get();
        $setores = Agrupamento::where('tipo', 'setor')->get();
        $membros = Membro::all();

        return view('reunioes.includes.form_criar', ['grupos' => $grupos, 'departamentos' => $departamentos, 'setores' => $setores, 'membros' => $membros]);
    }

    public function form_editar($id){
        $grupos = Agrupamento::where('tipo', 'grupo')->get();
        $departamentos = Agrupamento::where('tipo', 'departamento')->get();
        $setores = Agrupamento::where('tipo', 'setor')->get();
        $membros = Membro::all();
        $reuniao = Reuniao::findOrFail($id);

        return view('reunioes.includes.form_editar', ['reuniao' => $reuniao, 'grupos' => $grupos, 'departamentos' => $departamentos, 'setores' => $setores, 'membros' => $membros]);
    }
}
