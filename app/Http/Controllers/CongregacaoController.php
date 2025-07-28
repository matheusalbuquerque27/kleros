<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Congregacao;
use App\Models\CongregacaoConfig;
use App\Models\Denominacao;
use Illuminate\Http\Request;

class CongregacaoController extends Controller
{
    public function index()
    {   
        $congregacoes = Congregacao::all();

        return view('congregacoes.perfil', ['congregacoes' => $congregacoes]);
    }

    public function create()
    {
        $denominacoes = Denominacao::all();

        return view('congregacoes.cadastro', ['denominacoes' => $denominacoes]);
    }

    public function store(Request $request)
    {
        $congregacao = new Congregacao;

        $request->validate([
            'nome' => 'required',
            'endereco' => 'required',
            'telefone' => 'required',
        ], [
            '*.required' => 'Nome, Endereço e Telefone são obrigatórios'
        ]);

        $congregacao->nome = $request->nome;
        $congregacao->endereco = $request->endereco;
        $congregacao->telefone = $request->telefone;
        $congregacao->created_at = now();
        $congregacao->updated_at = now();

        $congregacao->save();

        return redirect()->route('congregacoes.cadastro')->with('msg', 'Congregação cadastrada com sucesso!');
    }

    public function config($id)
    {
        $config = CongregacaoConfig::find($id);

        return view('congregacoes.config', ['config' => $config]);
    }

    public function update($id)
    {
        $congregacao = app('congregacao');
        $config = CongregacaoConfig::find($id);

        return view('congregacoes.edicao', ['config' => $config, 'congregacao' => $congregacao]);
    }
}
