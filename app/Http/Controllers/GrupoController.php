<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Agrupamento;
use App\Models\GrupoIntegrante;
use App\Models\Membro;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    public function create(){

        $membros = Membro::all();

        return view('grupos/cadastro', ['membros' => $membros]);
    }

    public function store(Request $request){

        $grupo = new Agrupamento;

        $msg = "O grupo ".$request->nome." foi adicionado.";

        $grupo->nome = $request->nome;
        $grupo->created_at = date('Y/m/d');
        $grupo->updated_at = date('Y/m/d');
        $grupo->descricao = $request->descricao;
        $grupo->lider_id = $request->lider_id;
        $grupo->colider_id = $request->colider_id;
        $grupo->congregacao_id = app('congregacao')->id;

        $grupo->save();

        return redirect('/cadastros#grupos')->with('msg', $msg);
    }

    public function update(Request $request, $id) {
        $grupo = Agrupamento::findOrFail($id);

        $msg = "O grupo ".$request->nome." foi atualizado.";

        $grupo->nome = $request->nome;
        $grupo->descricao = $request->descricao;
        $grupo->lider_id = $request->lider_id;
        $grupo->colider_id = $request->colider_id;
        $grupo->updated_at = now();

        $grupo->save();

        return redirect('/cadastros#grupos')->with('msg', $msg);
    }

    public function show($id){

        $grupo = Agrupamento::find($id);
        $integrantes = $grupo->integrantes;

        $membros = Membro::whereDoesntHave('gruposMembro', function ($query) use ($grupo) {
            $query->where('agrupamento_id', $grupo->id);
        })->get(); //Não pertencem ainda ao grupo;

        return view('/grupos/integrantes', ['grupo' => $grupo, 'integrantes' => $integrantes, 'membros' => $membros]);
    }

    public function addMember(Request $request){

        $membro = Membro::find($request->membro);
        $grupo = Agrupamento::find($request->grupo);

        $membro->gruposMembro()->attach($grupo, [
            'congregacao_id' => $grupo->congregacao_id
        ]);

        return redirect()->route('grupos.integrantes', ['id' => $grupo->id])->with("Novo membro adicionado ao grupo.");
    }

    public function destroy($id){
        $grupo = Agrupamento::find($id);

        $grupo->delete();        

        return response()->json(['success' => true, 'message' => 'Grupo excluído com sucesso!']);
    }

    public function print($data) {
        
        $grupos = Agrupamento::all();
        
        //Cria um objeto para armazenar os membros por ministério
        $membrosByGrupo = new \stdClass();

        foreach($grupos as $grupo) {
            $membros = GrupoIntegrante::where('grupo_id', $grupo->id)->get();

            $membrosByGrupo->{$grupo->nome} = $membros;
        }
        

        return view('impressoes/print-grupos', compact('membrosByGrupo'));
    }

    public function form_editar($id) {
        $grupo = Agrupamento::findOrFail($id);
        $membros = Membro::all();
        return view('grupos/includes/form_editar', compact('grupo', 'membros'));
    }
}
