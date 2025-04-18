<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use App\Models\GrupoIntegrante;
use App\Models\Membro;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    public function create(){

        $membros = Membro::all();

        return view('grupos/grupos', ['membros' => $membros]);
    }

    public function store(Request $request){

        $grupo = new Grupo;

        $msg = "O grupo ".$request->nome." foi adicionado.";

        $grupo->nome = $request->nome;
        $grupo->created_at = date('Y/m/d');
        $grupo->updated_at = date('Y/m/d');
        $grupo->descricao = $request->descricao;
        $grupo->membro_id = $request->membro_id;

        $grupo->save();

        return redirect('/cadastros#grupos')->with('msg', $msg);
    }

    public function show($id){

        $grupo = Grupo::find($id);
        $integrantes = $grupo->integrantes;

        $membros = Membro::whereDoesntHave('gruposMembro', function ($query) use ($grupo) {
            $query->where('grupo_id', $grupo->id);
        })->get(); //Não pertencem ainda ao grupo;

        return view('/grupos/integrantes', ['grupo' => $grupo, 'integrantes' => $integrantes, 'membros' => $membros]);
    }

    public function addMember(Request $request){

        $membro = Membro::find($request->membro);
        $grupo = Grupo::find($request->grupo);

        $membro->gruposMembro()->attach($grupo);

        return redirect()->route('grupos.integrantes', ['id' => $grupo->id])->with("Novo membro adicionado ao grupo.");
    }

    public function destroy($id){
        $grupo = Grupo::find($id);

        $grupo->delete();        

        return response()->json(['success' => true, 'message' => 'Grupo excluído com sucesso!']);
    }

    public function print($data) {
        
        $grupos = Grupo::all();
        
        //Cria um objeto para armazenar os membros por ministério
        $membrosByGrupo = new \stdClass();

        foreach($grupos as $grupo) {
            $membros = GrupoIntegrante::where('grupo_id', $grupo->id)->get();

            $membrosByGrupo->{$grupo->nome} = $membros;
        }
        

        return view('impressoes/print-grupos', compact('membrosByGrupo'));
    }
}
