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
            'assunto as title',
            'data_inicio as start',
        ])->where('congregacao_id', $congregacao->id)->get();

        $cultos = Culto::select([
            'id',
            'data_culto as start',
        ])->where('congregacao_id', $congregacao->id)->get();

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
            return [
                'id' => $evento->id,
                'title' => $evento->title,
                'start' => Carbon::parse($evento->data_inicio)->toDateString(),
                'end' => $evento->data_encerramento
                    ? Carbon::parse($evento->data_encerramento)->addDay()->toDateString()
                    : null,
                'color' => '#2196f3',
            ];
        });

        $reunioes = $reunioes->map(function ($item) {
            $item->color = '#eb8b1e';
            $item->backgroundColor = '#eb8b1e';

            return $item;
        });

        $cultos = $cultos->map(function ($item) {
            $item->title = 'Culto';
            $item->color = '#4caf50';

            return $item;
        });

        $todosEventos = $cultos
            ->concat($eventos)
            ->concat($reunioes)
            ->concat($aniversarios)
            ->values();

        return response()->json($todosEventos);
    }
}
