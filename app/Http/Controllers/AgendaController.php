<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Congregacao;
use App\Models\Membro;
use Illuminate\Http\Request;
use App\Models\Culto;
use App\Models\Evento;
use App\Models\Reuniao;

class AgendaController extends Controller
{
    public function index()
    {
        $congregacao = app('congregacao');

        return view('agenda.index', compact('congregacao'));
    }

    public function eventosJson()
    {
        $congregacao = app('congregacao');

        if (! $congregacao) {
            return response()->json([]);
        }

        $reunioes = Reuniao::select([
            'id',
            'assunto',
            'data_inicio',
        ])->where('congregacao_id', $congregacao->id)->get()
            ->map(function ($reuniao) {
                return [
                    'id' => 'reuniao-' . $reuniao->id,
                    'title' => $reuniao->assunto,
                    'start' => Carbon::parse($reuniao->data_inicio)->toIso8601String(),
                    'color' => '#eb8b1e',
                    'backgroundColor' => '#eb8b1e',
                    'extendedProps' => [
                        'type' => 'reuniao',
                        'editUrl' => route('reunioes.form_editar', $reuniao->id),
                    ],
                ];
            });

        $cultos = Culto::select([
            'id',
            'data_culto',
            'preletor',
        ])->where('congregacao_id', $congregacao->id)->get()
            ->map(function ($culto) {
                $title = 'Culto';

                if (! empty($culto->preletor)) {
                    $title .= ' - ' . $culto->preletor;
                }

                return [
                    'id' => 'culto-' . $culto->id,
                    'title' => $title,
                    'start' => Carbon::parse($culto->data_culto)->toDateString(),
                    'color' => '#4caf50',
                    'backgroundColor' => '#4caf50',
                    'extendedProps' => [
                        'type' => 'culto',
                        'editUrl' => route('cultos.form_editar', $culto->id),
                    ],
                ];
            });

        $aniversarios = Membro::select([
            'id',
            'nome',
            'data_nascimento',
        ])->where('congregacao_id', $congregacao->id)
            ->whereNotNull('data_nascimento')
            ->get()
            ->map(function ($membro) {
            $data = Carbon::parse($membro->data_nascimento)->setYear(now()->year);

            if ($data->isPast()) {
                $data = $data->addYear();
            }

            return [
                'id' => 'birthday-' . $membro->id,
                'title' => '<i class="bi bi-cake2"></i> ' . $membro->nome,
                'start' => $data->toDateString(),
                'color' => '#d4a017',
                'backgroundColor' => '#d4a017',
                'extendedProps' => [
                    'type' => 'aniversario',
                    'editUrl' => null,
                ],
            ];
        });

        $eventos = Evento::select([
            'id',
            'titulo as title',
            'data_inicio',
            'data_encerramento',
        ])->where('congregacao_id', $congregacao->id)
            ->get()
            ->map(function ($evento) {
                $start = Carbon::parse($evento->data_inicio);
                $end = null;

                if ($evento->data_encerramento) {
                    $endDate = Carbon::parse($evento->data_encerramento);

                    if ($endDate->greaterThan($start)) {
                        $end = $endDate->copy()->addDay()->toDateString();
                    }
                }

            return [
                'id' => 'evento-' . $evento->id,
                'title' => $evento->title,
                'start' => $start->toIso8601String(),
                'end' => $end,
                'color' => '#2196f3',
                'backgroundColor' => '#2196f3',
                'extendedProps' => [
                    'type' => 'evento',
                    'editUrl' => route('eventos.form_editar', $evento->id),
                ],
            ];
        });

        $todosEventos = $cultos
            ->concat($eventos)
            ->concat($reunioes)
            ->concat($aniversarios)
            ->values();

        return response()->json($todosEventos);
    }
}
