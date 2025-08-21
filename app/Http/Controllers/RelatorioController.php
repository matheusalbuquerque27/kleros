<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Charts\VisitantesPorMesChart;

class RelatorioController extends Controller {
    public function painel(VisitantesPorMesChart $chartBuilder)
    {
        $inicio = Carbon::parse('2025-02-01')->startOfDay();
        $fim    = Carbon::parse('2025-09-13')->endOfDay();

        // agrega por mês (YYYY-MM) no banco
        $rows = DB::table('visitantes as v')
            ->whereBetween('v.data_visita', [$inicio, $fim])
            ->selectRaw("DATE_FORMAT(v.data_visita, '%Y-%m') as ym, COUNT(*) as total_visitantes")
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        // período de meses contínuo
        $period = collect(
            CarbonPeriod::create($inicio->copy()->startOfMonth(), '1 month', $fim->copy()->startOfMonth())
        );

        // 1) CHAVES internas para mapear dados (YYYY-MM)
        $ymKeys = $period->map(fn($d) => $d->format('Y-m'))->values()->all();

        // 2) LABELS de exibição (Fev/2025 em pt-BR)
        $labels = collect(
            CarbonPeriod::create($inicio->copy()->startOfMonth(), '1 month', $fim->copy()->startOfMonth())
        )->map(function ($d) {
            // "fev/2025" com locale pt_BR
            $s = $d->locale('pt_BR')->isoFormat('MMM[/]YYYY');
            // "Fev/2025" (title case, respeita acentos)
            return mb_convert_case($s, MB_CASE_TITLE, 'UTF-8');
        })->values()->all();
        
        // 3) monta os dados alinhados usando as CHAVES YYYY-MM
        $map  = $rows->keyBy('ym'); // '2025-02' => total
        $data = array_map(fn($ym) => (int) ($map[$ym]->total_visitantes ?? 0), $ymKeys);

        // 4) build do chart usa labels bonitos + data alinhado
        $chart = $chartBuilder->build($labels, $data);

        return view('relatorios.painel', [
            'chart'  => $chart,
            'inicio' => $inicio->toDateString(),
            'fim'    => $fim->toDateString(),
        ]);
    }
}