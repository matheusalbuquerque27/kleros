<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Culto;
use App\Models\SituacaoVisitante;
use App\Models\Visitante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class VisitanteController extends Controller
{
    public function create() {

        $situacao_visitante = SituacaoVisitante::all();
        $congregacao = app('congregacao');

        return view('visitantes/cadastro', ['situacao_visitante' => $situacao_visitante, 'congregacao' => $congregacao]);
    }

    public function store(Request $request) {

        $visitante = new Visitante;
        $msg = $request->nome.' foi cadastrado(a) como visitante!';

        $request->validate([
            'nome' => 'required',
            'telefone' => 'required',
            'data_visita' => 'required'
        ], [
            '*.required' => 'Nome do visitante, Telefone e Data de visita são obrigatórios',
        ]);    

        $visitante->nome = $request->nome;
        $visitante->telefone = $request->telefone;
        $visitante->data_visita = $request->data_visita;
        $visitante->sit_visitante_id = $request->situacao;
        $visitante->observacoes = $request->observacoes;
        $visitante->congregacao_id = app('congregacao')->id;
        $visitante->created_at = date('Y-m-d H:i:s');
        $visitante->updated_at = date('Y-m-d H:i:s');

        $visitante->save();

        return redirect()->route('visitantes.adicionar')->with('msg', $msg);    
    }

    public function historico() {

        $visitantes = Visitante::all();

        return view('visitantes/historico', ['visitantes' => $visitantes]);
    }

    public function search(Request $request) {
        
        $nome = '%'. $request->nome .'%';
        $data_visita = $request->data_visita;

        $visitantes = Visitante::whereDate('data_visita', $data_visita)->orWhere('nome','LIKE', $nome)->get();
        $visitantes = $visitantes->isEmpty() ? '' : $visitantes;

        $view = view('visitantes/visitantes_search', ['visitantes' => $visitantes])->render();

        return response()->json(['view' => $view]);

    }

}
