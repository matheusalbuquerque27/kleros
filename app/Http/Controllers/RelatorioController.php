<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Charts\VisitantesPorMesChart; // sua classe em app/Charts


class RelatorioController extends Controller
{
    public function painel(VisitantesPorMesChart $chartBuilder)
    {

            // 1) período
        $inicio = Carbon::parse('2025-02-01')->startOfDay();
        $fim    = Carbon::parse('2025-09-13')->endOfDay();

        // 2) agrega no banco por mês (YYYY-MM)
        $rows = DB::table('visitantes as v')
            ->whereBetween('v.data_visita', [$inicio, $fim])
            ->selectRaw("DATE_FORMAT(v.data_visita, '%Y-%m') as ym, COUNT(*) as total_visitantes")
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        // 3) gera a lista contínua de meses entre início e fim (evita buracos no gráfico)
        $labels = collect(
            CarbonPeriod::create($inicio->copy()->startOfMonth(), '1 month', $fim->copy()->startOfMonth())
        )->map(fn ($d) => $d->format('Y-m')) // rótulos neutros; se preferir "Fev/2025", veja nota abaixo
        ->values()
        ->all();

        // 4) mapeia dados para cada mês (preenche com 0 quando não veio do BD)
        $map = $rows->keyBy('ym');
        $data = array_map(fn($ym) => (int) ($map[$ym]->total_visitantes ?? 0), $labels);

        // 5) monta o gráfico com sua classe de chart

        $chart = $chartBuilder->build($labels, $data);

        return view('relatorios.painel', [
            'chart'  => $chart,
            'inicio' => $inicio->toDateString(),
            'fim'    => $fim->toDateString(),
        ]);
    }
}
