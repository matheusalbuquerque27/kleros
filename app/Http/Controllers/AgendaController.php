<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Congregacao;
use Illuminate\Http\Request;
use App\Models\Culto;
use App\Models\Evento;
use App\Models\Reuniao;

class AgendaController extends Controller
{
    public function index(){
        $congregacao = app('congregacao');
        return view('agenda.index', compact('congregacao'));
    }

    public function eventosJson()
    {
        $reunioes = Reuniao::select([
            'id',
            'assunto as title',
            'data_inicio as start',
            // pode adicionar 'hora' ou 'data_fim' se houver
        ])->get();

        $cultos = Culto::select([
            'id',
            'data_culto as start',
            // pode adicionar 'hora' ou 'data_fim' se houver
        ])->get();

        $eventos = Evento::select([
            'id',
            'titulo as title',
            'data_inicio',
            'data_encerramento'
        ])->get()->map(function ($evento) {
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
            $item->color = '#eb8b1e'; // laranja para reunioes
            $item->backgroundColor = '#eb8b1e'; // laranja para reunioes
            return $item;
        });

        $cultos = $cultos->map(function ($item) {
            $item->title = "Culto";
            $item->color = '#4caf50'; // verde para cultos
            return $item;
        });

        // Mesclar as duas coleções
        $todosEventos = $cultos->concat($eventos)->concat($reunioes)->values();

        return response()->json($todosEventos);
    }
}
