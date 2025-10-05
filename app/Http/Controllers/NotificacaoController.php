<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AvisoMembro;

class NotificacaoController extends Controller
{
    /**
     * Fetch user notifications.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = Auth::user();

        // Ensure user is authenticated and has a member profile
        if (!$user || !$user->membro) {
            return response()->json([
                'count' => 0,
                'avisos' => [],
            ]);
        }

        $membro = $user->membro;

        $avisos = $membro->avisosVisiveis();

        $leituras = AvisoMembro::whereIn('aviso_id', $avisos->pluck('id'))
            ->where('membro_id', $membro->id)
            ->get()
            ->keyBy('aviso_id');

        $naoLidos = $avisos->filter(function ($aviso) use ($leituras) {
            $leitura = $leituras->get($aviso->id);
            return !$leitura || !$leitura->lido;
        })->take(10);

        $payload = $naoLidos->map(function ($aviso) {
            return [
                'id' => $aviso->id,
                'titulo' => $aviso->titulo,
                'mensagem' => $aviso->mensagem,
                'prioridade' => $aviso->prioridade,
                'created_at' => optional($aviso->created_at)->toIso8601String(),
            ];
        })->values();

        return response()->json([
            'count' => $payload->count(),
            'avisos' => $payload,
        ]);
    }
}
