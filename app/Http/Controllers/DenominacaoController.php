<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Agrupamento;
use App\Models\BaseDoutrinaria;
use App\Models\Denominacao;
use App\Models\Ministerio;
use App\Models\MinisterioEclesiastico;
use Illuminate\Http\Request;

class DenominacaoController extends Controller
{
    public function create()
    {
        $bases_doutrinarias = BaseDoutrinaria::all();

        return view('denominacoes.cadastro', compact('bases_doutrinarias'));
    }

    public function store(Request $request)
    {
        $denominacao = new Denominacao;

        $request->validate([
            'nome' => 'required|string|max:255',
            'base_doutrinaria' => 'required|exists:bases_doutrinarias,id',
            'ministerios_eclesiasticos' => 'required',
        ], [
            '*.required' => 'É obrigatório preencher todas as informações.',
            '*.string' => 'O título deve ser uma string válida.',
            '*.max' => 'O título não pode exceder 255 caracteres.',
        ]);

        $denominacao->nome = $request->nome;
        $denominacao->nome_curto = $request->nome_curto;
        $denominacao->base_doutrinaria = $request->base_doutrinaria;
        $denominacao->ativa = true;
        $denominacao->ministerios_eclesiasticos = $request->ministerios_eclesiasticos;

        if($denominacao->save()){
            session(['denominacao_id' => $denominacao->id]);

            if($denominacao->ministerios_eclesiasticos){

                // Decodifica o JSON dos ministérios eclesiásticos
                $ministerios = json_decode($denominacao->ministerios_eclesiasticos, true);

                //Criar os ministérios eclesiásticos
                foreach ($ministerios as $ministerio) {
                    $novoMinisterio = new Ministerio();
                    $novoMinisterio->titulo = $ministerio;
                    $novoMinisterio->denominacao_id = $denominacao->id;
                    $novoMinisterio->save();
                }

            } 

        } else {
            return redirect()->back()->with('error', 'Erro ao cadastrar a denominação.');
        }

        return redirect()->route('congregacoes.cadastro')->with('success', 'Denominação cadastrada com sucesso!');
    }
}
