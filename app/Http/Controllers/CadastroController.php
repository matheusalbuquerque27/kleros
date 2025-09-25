<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Agrupamento;
use App\Models\Celula;
use App\Models\Culto;
use App\Models\Evento;
use App\Models\Ministerio;
use App\Models\Reuniao;
use App\Models\Visitante;
use Illuminate\Support\Facades\Cache;
use App\Models\Curso;
use App\Models\Pesquisa;

class CadastroController extends Controller
{
    public function index() {

        $congregacao = app('congregacao');
        $congregacaoId = $congregacao->id;
        $now = now();

        //Esta parte verifica se há cultos cadastrados para os próximos dias
        $cultos = Culto::where('congregacao_id', $congregacaoId)
            ->whereDate('data_culto', '>', $now->toDateString())
            ->orderBy('data_culto', 'asc')
            ->limit(3)
            ->get();
        
        //Esta parte verifica se há eventos cadastrados para os próximos dias
        $eventos = Evento::where('congregacao_id', $congregacaoId)
            ->where('recorrente', false)
            ->whereDate('data_inicio', '>', $now->toDateString())
            ->orderBy('data_inicio', 'asc')
            ->limit(4)
            ->get();

        //Reuniões
        $reunioes = Reuniao::where('congregacao_id', $congregacaoId)
            ->where('data_inicio', '>=', $now)
            ->orderBy('data_inicio', 'asc')
            ->limit(3)
            ->get();

        $cursos = Curso::where('ativo', true)
            ->where('congregacao_id', $congregacaoId)
            ->orderBy('titulo')
            ->get();

        /*Essa parte verifica o tal de visitantes do mês, se não houver ele receberá uma string vazia*/
        $visitantes_mes = Visitante::where('congregacao_id', $congregacaoId)
            ->whereMonth('data_visita', $now->month)
            ->whereYear('data_visita', $now->year)
            ->count();

        $ministerios = Ministerio::where('denominacao_id', $congregacao->denominacao_id)
            ->orderBy('titulo')
            ->get();

        $grupos = Agrupamento::where('congregacao_id', $congregacaoId)
            ->where('tipo', 'grupo')
            ->get();
        $departamentos = Agrupamento::where('congregacao_id', $congregacaoId)
            ->where('tipo', 'departamento')
            ->get();
        $celulas = Celula::where('congregacao_id', $congregacaoId)->get();

        $pesquisas = Pesquisa::with('criador')
            ->forCongregacao($congregacaoId)
            ->where(function ($query) use ($now) {
                $query->whereNull('data_fim')
                    ->orWhereDate('data_fim', '>=', $now->toDateString());
            })
            ->orderByDesc('data_inicio')
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        $noticias = Cache::get('noticias_feed') ?? [];
        $destaques = array_slice($noticias['guiame'] ?? [], 0, 9);

        return view('/cadastros', [
            'eventos' => $eventos,
            'grupos' => $grupos,
            'ministerios' => $ministerios,
            'cultos' => $cultos,
            'visitantes_total' => $visitantes_mes,
            'reunioes' => $reunioes,
            'cursos' => $cursos,
            'congregacao' => $congregacao,
            'departamentos' => $departamentos,
            'celulas' => $celulas,
            'pesquisas' => $pesquisas,
            'destaques' => $destaques,
        ]);
    }
}
