<?php

namespace App\Services;

use App\Models\Aviso;
use App\Models\AvisoMembro;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class AvisoService
{
    /**
     * Cria um aviso e registra destinatários opcionais.
     */
    public static function enviar(array $dados): Aviso
    {
        $congregacao = app('congregacao');

        $titulo = trim($dados['titulo'] ?? '');
        $mensagem = trim($dados['mensagem'] ?? '');

        if ($titulo === '' || $mensagem === '') {
            throw new RuntimeException('Título e mensagem são obrigatórios para criar um aviso.');
        }

        $criadoPor = $dados['criado_por'] ?? Auth::id();
        if (!$criadoPor) {
            throw new RuntimeException('Não foi possível identificar o autor do aviso.');
        }

        $membros = collect($dados['membros'] ?? [])
            ->flatten()
            ->filter(static fn ($id) => filled($id))
            ->map(static fn ($id) => (int) $id)
            ->unique()
            ->values();

        $grupos = collect($dados['grupos'] ?? [])
            ->flatten()
            ->filter(static fn ($id) => filled($id))
            ->map(static fn ($id) => (int) $id)
            ->unique()
            ->values();

        $paraTodos = (bool) ($dados['para_todos'] ?? false);
        if (!$paraTodos && $grupos->isEmpty() && $membros->isEmpty()) {
            $paraTodos = true;
        }

        $aviso = Aviso::create([
            'congregacao_id' => $congregacao->id,
            'titulo' => $titulo,
            'mensagem' => $mensagem,
            'para_todos' => $paraTodos,
            'destinatarios_agrupamentos' => $grupos->isNotEmpty() ? $grupos->all() : null,
            'data_inicio' => $dados['data_inicio'] ?? now(),
            'data_fim' => $dados['data_fim'] ?? null,
            'status' => $dados['status'] ?? 'ativo',
            'prioridade' => $dados['prioridade'] ?? 'normal',
            'criado_por' => $criadoPor,
        ]);

        if ($membros->isNotEmpty()) {
            $membros->each(static function (int $membroId) use ($aviso) {
                AvisoMembro::updateOrCreate(
                    [
                        'aviso_id' => $aviso->id,
                        'membro_id' => $membroId,
                    ],
                    ['lido' => false]
                );
            });
        }

        return $aviso;
    }
}
