<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Culto;
use App\Models\Evento;
use App\Models\Grupo;
use App\Models\Ministerio;
use App\Models\Visitante;
use Illuminate\Http\Request;

class CadastroController extends Controller
{
    public function index() {

        /*Esta parte verifica se há cultos cadastrados para os próximos dias

            Se não houver ele envia uma informação vazia, com mensagem sobre a ausencia de cultos.
        */
        $cultos = Culto::whereDate('data_culto', '>=', date('Y/m/d'))->limit(4)->orderBy('data_culto', 'asc')->get();
        $cultos = $cultos->isEmpty() ? '' : $cultos;

        /*Esta parte verifica se há eventos cadastrados para os próximos dias

            Se não houver ele envia uma informação vazia, com mensagem sobre a ausencia de eventos.
        */
        $eventos = Evento::whereDate('data_evento', '>', date('Y/m/d'))->limit(4)->orderBy('data_evento', 'asc')->get();
        $eventos = $eventos->isEmpty() ? '' : $eventos;

        /*Essa parte verifica o tal de visitantes do mês, se não houver ele receberá uma string vazia*/
        $visitantes_mes = Visitante::whereMonth('data_visita', date('m'))->count();
        $visitantes_mes = $visitantes_mes ? $visitantes_mes : '';

        $ministerios = Ministerio::whereNot('id', 1)->get();

        $grupos = Grupo::all();

        return view('/cadastros', ['eventos' => $eventos, 'grupos' => $grupos, 'ministerios' => $ministerios, 'cultos' => $cultos, 'visitantes_total' => $visitantes_mes]);
    }
}
