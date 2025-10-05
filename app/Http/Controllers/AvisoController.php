<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aviso;
use App\Models\Membro;
use App\Models\Agrupamento;
use Illuminate\Support\Facades\Auth;
use App\Models\AvisoMembro;
use Illuminate\Http\JsonResponse;
use App\Services\AvisoService;

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

        $avisos = $membro->avisosVisiveis();

        $avisos->load('criador.membro.ministerio');

        $leituras = AvisoMembro::whereIn('aviso_id', $avisos->pluck('id'))
            ->where('membro_id', $membro->id)
            ->get()
            ->keyBy('aviso_id');

        $avisos->transform(function ($aviso) use ($leituras) {
            $aviso->is_lido = optional($leituras->get($aviso->id))->lido ?? false;
            return $aviso;
        });

        return view('avisos.painel', compact('avisos', 'membro', 'congregacao'));
    }

    public function store(Request $request)
    {
        $paraTodos = (bool) $request->input('destinatarios', true);

        AvisoService::enviar([
            'titulo' => $request->input('titulo'),
            'mensagem' => $request->input('mensagem'),
            'prioridade' => $request->input('prioridade', 'normal'),
            'para_todos' => $paraTodos,
            'grupos' => $paraTodos ? [] : $request->input('grupos', []),
            'membros' => $paraTodos ? [] : $request->input('membros', []),
            'data_fim' => $request->input('data_fim') ?: null,
            'criado_por' => Auth::id(),
        ]);

        return redirect()
            ->route('avisos.painel')
            ->with('msg', 'Aviso criado com sucesso!');
    }

    public function form_criar() {
        $grupos = Agrupamento::all();
        $membros = Membro::all();
        return view('avisos/includes/form_criar', compact('membros', 'grupos'));    
    }

    public function show(Aviso $aviso): JsonResponse
    {
        $user = Auth::user();

        if (!$user || !$user->membro) {
            abort(403, 'Usuário não tem membro associado');
        }

        $membro = $user->membro;
        $congregacao = app('congregacao');

        $membro->loadMissing('agrupamentos');

        abort_unless($aviso->congregacao_id === $congregacao->id, 403);
        abort_unless($this->membroPodeVerAviso($aviso, $membro), 403);

        $this->marcarAvisoComoLido($aviso, $membro);

        $aviso->load('criador.membro.ministerio');

        $criador = optional($aviso->criador);
        $criadorMembro = optional($criador->membro);
        $ministerio = optional($criadorMembro->ministerio);

        return response()->json([
            'id' => $aviso->id,
            'titulo' => $aviso->titulo,
            'mensagem' => $aviso->mensagem,
            'prioridade' => $aviso->prioridade,
            'criado_em' => optional($aviso->created_at)->toIso8601String(),
            'enviado_por' => trim(collect([
                $ministerio->sigla,
                $criadorMembro->nome ? primeiroEUltimoNome($criadorMembro->nome) : null,
            ])->filter()->implode(' ')),
            'lido' => true,
        ]);
    }

    protected function membroPodeVerAviso(Aviso $aviso, Membro $membro): bool
    {
        if ($aviso->para_todos) {
            return true;
        }

        if ($aviso->membros()->where('membro_id', $membro->id)->exists()) {
            return true;
        }

        $destinatarios = $aviso->destinatarios_agrupamentos;
        if (is_array($destinatarios) && !empty($destinatarios)) {
            $grupoIds = $membro->agrupamentos->pluck('id')->toArray();
            return count(array_intersect($destinatarios, $grupoIds)) > 0;
        }

        return false;
    }

    protected function marcarAvisoComoLido(Aviso $aviso, Membro $membro): void
    {
        AvisoMembro::updateOrCreate(
            [
                'aviso_id' => $aviso->id,
                'membro_id' => $membro->id,
            ],
            ['lido' => true]
        );
    }
}
