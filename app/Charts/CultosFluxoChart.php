<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class CultosFluxoChart
{
    public function build(array $labels, array $adultos, array $criancas, array $visitantes): LarapexChart
    {
        $labels = array_values(array_map('strval', $labels));
        $adultos = array_values(array_map('intval', $adultos));
        $criancas = array_values(array_map('intval', $criancas));
        $visitantes = array_values(array_map('intval', $visitantes));

        $max = min(count($labels), count($adultos), count($criancas), count($visitantes));
        $labels = array_slice($labels, 0, $max);
        $adultos = array_slice($adultos, 0, $max);
        $criancas = array_slice($criancas, 0, $max);
        $visitantes = array_slice($visitantes, 0, $max);

        return (new LarapexChart)
            ->lineChart()
            ->setHeight(380)
            ->setDataset([
                ['name' => 'Adultos', 'data' => $adultos],
                ['name' => 'Crianças', 'data' => $criancas],
                ['name' => 'Visitantes', 'data' => $visitantes],
            ])
            ->setColors(['#2563eb', '#f97316', '#22c55e'])
            ->setXAxis($labels)
            ->setMarkers(['#2563eb', '#f97316', '#22c55e'])
            ->setToolbar(true)
            ->setGrid();
    }
}
