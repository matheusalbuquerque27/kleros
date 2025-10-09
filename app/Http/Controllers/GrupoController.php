<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Agrupamento;
use App\Models\GrupoIntegrante;
use App\Models\Membro;
use App\Models\Setor;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    
    public function store(Request $request){

        $grupo = new Agrupamento;

        $msg = "O grupo ".$request->nome." foi adicionado.";

        $congregacaoId = app('congregacao')->id;

        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'lider_id' => 'required|exists:membros,id',
            'colider_id' => 'nullable|exists:membros,id',
            'setor_id' => [
                'nullable',
                Rule::exists('agrupamentos', 'id')->where(function ($query) use ($congregacaoId) {
                    $query->where('tipo', 'setor')
                        ->where('congregacao_id', $congregacaoId);
                }),
            ],
        ]);

        $grupo->nome = $request->nome;
        $grupo->created_at = date('Y/m/d');
        $grupo->updated_at = date('Y/m/d');
        $grupo->descricao = $request->descricao;
        $grupo->lider_id = $request->lider_id;
        $grupo->colider_id = $request->colider_id ?: null;
        $grupo->congregacao_id = $congregacaoId;
        $grupo->agrupamento_pai_id = $request->setor_id ?: null;
        $grupo->tipo = 'grupo';

        $grupo->save();

        return redirect('/cadastros#grupos')->with('msg', $msg);
    }

    public function form_criar(){

        $membros = Membro::DaCongregacao()->get();
        $setores = Setor::where('congregacao_id', app('congregacao')->id)
            ->orderBy('nome')
            ->get();

        return view('grupos/includes/form_criar', ['membros' => $membros, 'setores' => $setores]);
    }

    public function update(Request $request, $id) {
        $grupo = Agrupamento::findOrFail($id);

        $congregacaoId = app('congregacao')->id;

        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'lider_id' => 'required|exists:membros,id',
            'colider_id' => 'nullable|exists:membros,id',
            'setor_id' => [
                'nullable',
                Rule::exists('agrupamentos', 'id')->where(function ($query) use ($congregacaoId) {
                    $query->where('tipo', 'setor')
                        ->where('congregacao_id', $congregacaoId);
                }),
            ],
        ]);

        $msg = "O grupo ".$request->nome." foi atualizado.";

        $grupo->nome = $request->nome;
        $grupo->descricao = $request->descricao;
        $grupo->lider_id = $request->lider_id;
        $grupo->colider_id = $request->colider_id ?: null;
        $grupo->agrupamento_pai_id = $request->setor_id ?: null;
        $grupo->tipo = 'grupo';
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
        $membros = Membro::DaCongregacao()->whereDoesntHave('agrupamentos', function ($query) use ($agrupamento) {
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

    public function removeMember($grupoId, $membroId)
    {
        $agrupamento = Agrupamento::findOrFail($grupoId);
        $agrupamento->integrantes()->detach($membroId);

        return redirect()
            ->route('grupos.integrantes', ['id' => $agrupamento->id])
            ->with('msg', 'Membro removido do grupo.');
    }

    public function destroy(Request $request, $id){
        $grupo = Agrupamento::findOrFail($id);

        $grupo->delete();

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Grupo excluído com sucesso!']);
        }

        return redirect('/cadastros#grupos')->with('msg', 'Grupo excluído com sucesso!');
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
        $membros = Membro::DaCongregacao()->get();
        $setores = Setor::where('congregacao_id', app('congregacao')->id)
            ->orderBy('nome')
            ->get();
        return view('grupos/includes/form_editar', compact('grupo', 'membros', 'setores'));
    }

}
