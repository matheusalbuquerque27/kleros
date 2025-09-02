<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aviso;
use App\Models\Membro;
use App\Models\Agrupamento;
use Illuminate\Support\Facades\Auth;

class AvisoController extends Controller
{
    public function index() {
        $congregacao = app('congregacao');
        $avisos = Aviso::where('congregacao_id', $congregacao->id)->get();

        return view('avisos.painel', compact('avisos', 'congregacao'));
    }

    public function store(Request $request)
    {
        // debug: verificar se request chega
        // dd($request->all());

        $request->validate([
            'titulo'   => 'required|string|max:255',
            'mensagem' => 'required|string',
        ]);

        $aviso = new Aviso();
        $aviso->congregacao_id = app('congregacao')->id;
        $aviso->titulo = $request->titulo;
        $aviso->mensagem = $request->mensagem;
        $aviso->para_todos = $request->para_todos ?? false;
        $aviso->status = 'ativo';
        $aviso->prioridade = 'normal';
        $aviso->criado_por = Auth::id(); // precisa de user logado
        $aviso->save();

        return redirect()
            ->route('avisos.painel')
            ->with('msg', 'Aviso criado com sucesso!');
    }

    public function form_criar() {
        $grupos = Agrupamento::all();
        $membros = Membro::all();
        return view('avisos/includes/form_criar', compact('membros', 'grupos'));    
    }
}
