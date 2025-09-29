<?php

namespace App\Http\Controllers;

use App\Charts\MembrosPorFaixaEtariaChart;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Charts\VisitantesPorMesChart;
use App\Charts\MembrosPorSexoChart;
use App\Charts\CultosFrequenciaChart;
use App\Charts\CultosFluxoChart;

class RelatorioController extends Controller
{
    public function painel(
        VisitantesPorMesChart $chartBuilder,
        MembrosPorSexoChart $membrosChartBuilder,
        MembrosPorFaixaEtariaChart $membrosPorFaixaChartBuilder,
        CultosFrequenciaChart $cultosFrequenciaChartBuilder,
        CultosFluxoChart $cultosFluxoChartBuilder
    ) {
        $inicio = Carbon::parse('2025-02-01')->startOfDay();
        $fim    = Carbon::parse('2025-09-13')->endOfDay();

        // === Visitantes por mês ===
        $rows = DB::table('visitantes as v')
            ->whereBetween('v.data_visita', [$inicio, $fim])
            ->selectRaw("DATE_FORMAT(v.data_visita, '%Y-%m') as ym, COUNT(*) as total_visitantes")
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        $period = collect(
            CarbonPeriod::create($inicio->copy()->startOfMonth(), '1 month', $fim->copy()->startOfMonth())
        );

        $ymKeys = $period->map(fn($d) => $d->format('Y-m'))->values()->all();

        $labels = $period->map(function ($d) {
            $s = $d->locale('pt_BR')->isoFormat('MMM[/]YYYY');
            return mb_convert_case($s, MB_CASE_TITLE, 'UTF-8');
        })->values()->all();

        $map  = $rows->keyBy('ym');
        $data = array_map(fn($ym) => (int) ($map[$ym]->total_visitantes ?? 0), $ymKeys);

        $chartVisitantes = $chartBuilder->build($labels, $data);

        // === Membros por sexo ===
        $sexoStats = DB::table('membros')
            ->selectRaw('sexo, COUNT(*) as total')
            ->groupBy('sexo')
            ->pluck('total', 'sexo');

        $labelsMembros = $sexoStats->keys()->all();   // ['Masculino', 'Feminino']
        $dataMembros   = $sexoStats->values()->all(); // [120, 150]

        $chartMembros = $membrosChartBuilder->build($labelsMembros, $dataMembros);

        // === Membros por faixa etária (somente ativos) ===
        $faixas = DB::table('membros')
            ->where('ativo', true) // <-- filtro de ativos
            ->selectRaw("
                SUM(CASE WHEN TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) < 12 THEN 1 ELSE 0 END) as menores_12,
                SUM(CASE WHEN TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) BETWEEN 12 AND 17 THEN 1 ELSE 0 END) as de_12_18,
                SUM(CASE WHEN TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) BETWEEN 18 AND 29 THEN 1 ELSE 0 END) as de_18_30,
                SUM(CASE WHEN TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) BETWEEN 30 AND 59 THEN 1 ELSE 0 END) as de_30_60,
                SUM(CASE WHEN TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) >= 60 THEN 1 ELSE 0 END) as acima_60
            ")
            ->first();

        // total só de ativos
        $totalMembros = array_sum([
            $faixas->menores_12,
            $faixas->de_12_18,
            $faixas->de_18_30,
            $faixas->de_30_60,
            $faixas->acima_60,
        ]);

        $labelsFaixa = [
            'Menores de 12',
            '12 a 18',
            '18 a 30',
            '30 a 60',
            'Acima de 60'
        ];

        $dataFaixa = [
            $faixas->menores_12,
            $faixas->de_12_18,
            $faixas->de_18_30,
            $faixas->de_30_60,
            $faixas->acima_60,
        ];

        // gera labels com percentual
        $labelsFaixaComPct = array_map(function ($label, $valor) use ($totalMembros) {
            $pct = $totalMembros > 0 ? round(($valor / $totalMembros) * 100, 1) : 0;
            return "{$label} ({$pct}%)";
        }, $labelsFaixa, $dataFaixa);

        $chartFaixaEtaria = $membrosPorFaixaChartBuilder->build($labelsFaixaComPct, $dataFaixa);


        // === Frequência de cultos ===
        $cultosQuery = DB::table('cultos')
            ->where('congregacao_id', optional(app('congregacao'))->id)
            ->whereDate('data_culto', '<', Carbon::now()->toDateString())
            ->orderByDesc('data_culto')
            ->limit(6)
            ->get();

        $cultos = $cultosQuery->reverse()->values();

        $labelsCultos = $cultos->map(function ($culto) {
            $data = $culto->data_culto ?? $culto->created_at;
            return $data ? Carbon::parse($data)->format('d/m/Y') : 'Sem data';
        })->all();

        $adultosCulto = $cultos->map(fn($culto) => (int) ($culto->quant_adultos ?? 0))->all();
        $criancasCulto = $cultos->map(fn($culto) => (int) ($culto->quant_criancas ?? 0))->all();
        $visitantesCulto = $cultos->map(fn($culto) => (int) ($culto->quant_visitantes ?? 0))->all();

        $chartFrequenciaCultos = $cultosFrequenciaChartBuilder->build($labelsCultos, $adultosCulto, $criancasCulto, $visitantesCulto);
        $chartFluxoCultos = $cultosFluxoChartBuilder->build($labelsCultos, $adultosCulto, $criancasCulto, $visitantesCulto);

        // === Retorna view com os gráficos ===
        return view('relatorios.painel', [
            'chartVisitantes' => $chartVisitantes,
            'chartMembros'    => $chartMembros,
            'chartFaixaEtaria' => $chartFaixaEtaria,
            'chartFrequenciaCultos' => $chartFrequenciaCultos,
            'chartFluxoCultos' => $chartFluxoCultos,
            'inicio'          => $inicio->toDateString(),
            'fim'             => $fim->toDateString(),
        ]);
    }
}
