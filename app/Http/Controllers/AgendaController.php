<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Culto;
use App\Models\Evento;
use App\Models\Reuniao;

class AgendaController extends Controller
{
    public function index(){
        $eventos = Evento::orderBy('data_inicio')->get();
        $cultos = Culto::orderBy('data_culto')->get();
        $reunioes = Reuniao::orderBy('data_inicio')->get();
        return view('agenda.index', compact('eventos', 'cultos', 'reunioes'));
    }

    public function eventosJson()
    {
        $reunioes = Reuniao::select([
            'id',
            'titulo as title',
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
            'data_inicio as start',
            'data_encerramento as end'
        ])->get();

        $reunioes = $reunioes->map(function ($item) {
            $item->color = '#eb8b1eff'; // laranja para reunioes
            return $item;
        });

        $cultos = $cultos->map(function ($item) {
            $item->title = "Culto";
            $item->color = '#4caf50'; // verde para cultos
            return $item;
        });

        $eventos = $eventos->map(function ($item) {
            $item->color = '#2196f3'; // azul para eventos
            return $item;
        });

        // Mesclar as duas coleções
        $todosEventos = $cultos->concat($eventos)->concat($reunioes)->values();

        return response()->json($todosEventos);
    }
}
