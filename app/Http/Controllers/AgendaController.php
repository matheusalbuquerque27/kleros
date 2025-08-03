<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agenda;

class AgendaController extends Controller
{
    public function index(){
        $eventos = Agenda::orderBy('data_inicio')->get();
        return view('agenda.index', compact('eventos'));
    }

    public function eventosJson()
    {
        $eventos = Agenda::select(['id', 'titulo as title', 'inicio as start', 'fim as end'])->get();
        return response()->json($eventos);
    }
}
