<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\LancamentoFinanceiro;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LancamentosFinanceirosChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): LarapexChart
    {
        $startDate = Carbon::now()->subDays(15);
        $endDate = Carbon::now();

        $lancamentos = LancamentoFinanceiro::whereHas('caixa', function ($query) {
                $query->where('congregacao_id', optional(app('congregacao'))->id);
            })
            ->whereBetween('data_lancamento', [$startDate, $endDate])
            ->selectRaw('DATE(data_lancamento) as data, tipo, SUM(valor) as total')
            ->groupBy('data', 'tipo')
            ->orderBy('data')
            ->get();

        $dates = collect(CarbonPeriod::create($startDate, '1 day', $endDate))->map(function (Carbon $date) {
            return $date->format('Y-m-d');
        });

        $entradas = $lancamentos->where('tipo', 'entrada')->pluck('total', 'data');
        $saidas   = $lancamentos->where('tipo', 'saida')->pluck('total', 'data');

        $entradasData = [];
        $saidasData   = [];
        $saldoData    = [];
        $saldo        = 0;

        foreach ($dates as $date) {
            $entradaDiaria = $entradas->get($date, 0);
            $saidaDiaria   = $saidas->get($date, 0);

            // já manda formatado com 2 casas decimais
            $entradasData[] = number_format($entradaDiaria, 2, '.', '');
            $saidasData[]   = number_format($saidaDiaria, 2, '.', '');

            $saldo += $entradaDiaria - $saidaDiaria;
            $saldoData[] = number_format($saldo, 2, '.', '');
        }

        $labels = $dates->map(function ($date) {
            return Carbon::parse($date)->format('d/m');
        })->values();

        return (new LarapexChart)
            ->lineChart()
            ->setTitle('Últimos 15 dias')
            ->setHeight(380)
            ->setXAxis($labels->all())
            ->setDataset([
                [
                    'name' => 'Entradas',
                    'type' => 'column',
                    'data' => $entradasData,
                ],
                [
                    'name' => 'Saídas',
                    'type' => 'column',
                    'data' => $saidasData,
                ],
                [
                    'name' => 'Saldo',
                    'type' => 'line',
                    'data' => $saldoData,
                ],
            ])
            ->setColors(['#22c55e', '#ef4444', '#3b82f6']);
    }
}