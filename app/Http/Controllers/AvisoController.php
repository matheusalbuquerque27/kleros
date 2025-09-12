<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aviso;
use App\Models\Membro;
use App\Models\Agrupamento;
use Illuminate\Support\Facades\Auth;
use App\Models\AvisoMembro;

class AvisoController extends Controller
{
    // public function index() {
    //     $congregacao = app('congregacao');
    //     $avisos = Aviso::where('congregacao_id', $congregacao->id)->get();

    //     return view('avisos.', compact('avisos', 'congregacao'));
    // }

    public function avisosDoMembro()
    {
        $user = Auth::user();

        if (!$user || !$user->membro) {
            abort(403, 'Usuário não tem membro associado');
        }

        $membro = $user->membro;
        $congregacao = app('congregacao');

        // 1) avisos para todos
        $avisosParaTodos = Aviso::where('congregacao_id', $congregacao->id)
            ->where('para_todos', true)
            ->get();

        // 2) avisos individuais
        $avisosIndividuais = $membro->avisos()
            ->where('congregacao_id', $congregacao->id)
            ->get();

        // 3) avisos por grupos
        $grupoIds = $membro->agrupamentos->pluck('id')->toArray();
        $avisosPorGrupos = Aviso::where('congregacao_id', $congregacao->id)
            ->whereNotNull('destinatarios_agrupamentos')
            ->get()
            ->filter(function ($aviso) use ($grupoIds) {
                return is_array($aviso->destinatarios_agrupamentos)
                    && count(array_intersect($grupoIds, $aviso->destinatarios_agrupamentos)) > 0;
            });

        // juntar tudo
        $avisos = $avisosParaTodos
            ->merge($avisosIndividuais)
            ->merge($avisosPorGrupos)
            ->unique('id')
            ->values();

        return view('avisos.painel', compact('avisos', 'membro', 'congregacao'));
    }

    public function store(Request $request)
    {
        $aviso = new Aviso();
        $aviso->congregacao_id = app('congregacao')->id;
        $aviso->titulo = $request->titulo;
        $aviso->mensagem = $request->mensagem;
        $aviso->para_todos = $request->destinatarios;
        $aviso->status = 'ativo';
        $aviso->prioridade = $request->prioridade;
        $aviso->criado_por = Auth::id();   
        $aviso->data_inicio = now();
        if ($request->data_fim) {
            $aviso->data_fim = $request->data_fim;
        } else {
            $aviso->data_fim = null;
        }

        $aviso->destinatarios_agrupamentos = $request->has('grupos') 
        ? $request->grupos
        : null;

        $aviso->save();

        if ($request->has('membros')) {
        foreach ($request->membros as $membroId) {
            AvisoMembro::create([
                'aviso_id'  => $aviso->id,
                'membro_id' => $membroId,
                'lido'      => false,
            ]);
        }
    }

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
