<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Agrupamento;
use App\Models\Culto;
use App\Models\Evento;
use App\Models\Grupo;
use App\Models\Ministerio;
use App\Models\Reuniao;
use App\Models\Visitante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Curso;

class CadastroController extends Controller
{
    public function index() {

        //Esta parte verifica se há cultos cadastrados para os próximos dias
        $cultos = Culto::whereDate('data_culto', '>=', date('Y/m/d'))->limit(3)->orderBy('data_culto', 'asc')->get();
        
        //Esta parte verifica se há eventos cadastrados para os próximos dias
        $eventos = Evento::where('recorrente', false)->whereDate('data_inicio', '>', date('Y/m/d'))->limit(4)->orderBy('data_inicio', 'asc')->get();

        //Reuniões
        $reunioes = Reuniao::whereDate('data_inicio', '>=', date('Y/m/d H:i'))->limit(3)->orderBy('data_inicio', 'asc')->get();

        $cursos = Curso::where('ativo', true)->where('congregacao_id', app('congregacao')->id)->orderBy('titulo')->get();

        /*Essa parte verifica o tal de visitantes do mês, se não houver ele receberá uma string vazia*/
        $visitantes_mes = Visitante::whereMonth('data_visita', date('m'))->count();

        $ministerios = Ministerio::where('denominacao_id', app('congregacao')->denominacao_id)->orderBy('titulo')->get();

        $grupos = Agrupamento::where('tipo', 'grupo')->get();
        $congregacao = app('congregacao');

        $noticias = Cache::get('noticias_feed') ?? [];
        $destaques = array_slice($noticias['guiame'] ?? [], 0, 9);

        return view('/cadastros', ['eventos' => $eventos, 'grupos' => $grupos, 'ministerios' => $ministerios, 'cultos' => $cultos, 'visitantes_total' => $visitantes_mes, 'reunioes' => $reunioes, 'cursos' => $cursos, 'congregacao' => $congregacao, 'destaques' => $destaques]);
    }
}
