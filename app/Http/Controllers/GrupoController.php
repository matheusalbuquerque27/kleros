<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Agrupamento;
use App\Models\GrupoIntegrante;
use App\Models\Membro;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    
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

    public function form_criar(){

        $membros = Membro::all();

        return view('grupos/includes/form_criar', ['membros' => $membros]);
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

    public function show($id) {
        $congregacao = app('congregacao');
        $agrupamento = Agrupamento::findOrFail($id);

        // integrantes desse agrupamento
        $integrantes = $agrupamento->integrantes()->paginate(10);

        // membros que ainda NÃO pertencem a esse agrupamento
        $membros = Membro::whereDoesntHave('agrupamentos', function ($query) use ($agrupamento) {
            $query->where('agrupamento_id', $agrupamento->id);
        })->get();

        return view('grupos.integrantes', [
            'congregacao' => $congregacao,
            'grupo' => $agrupamento, // pode renomear pra 'agrupamento' na view também
            'integrantes' => $integrantes,
            'membros' => $membros
        ]);
    }

    public function addMember(Request $request) {
        $membro = Membro::findOrFail($request->membro);
        $agrupamento = Agrupamento::findOrFail($request->grupo);

        $membro->agrupamentos()->attach($agrupamento->id, [
            'congregacao_id' => $agrupamento->congregacao_id
        ]);

        return redirect()
            ->route('grupos.integrantes', ['id' => $agrupamento->id])
            ->with('msg', 'Novo membro adicionado ao agrupamento.');
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
