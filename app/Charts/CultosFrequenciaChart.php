<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class CultosFrequenciaChart
{
    public function build(array $labels, array $adultos, array $criancas, array $visitantes): LarapexChart
    {
        $labels = array_values(array_map('strval', $labels));
        $adultos = array_values(array_map('intval', $adultos));
        $criancas = array_values(array_map('intval', $criancas));
        $visitantes = array_values(array_map('intval', $visitantes));

        $maxLength = min(count($labels), count($adultos), count($criancas), count($visitantes));
        $labels = array_slice($labels, 0, $maxLength);
        $adultos = array_slice($adultos, 0, $maxLength);
        $criancas = array_slice($criancas, 0, $maxLength);
        $visitantes = array_slice($visitantes, 0, $maxLength);

        return (new LarapexChart)
            ->barChart()
            ->setHeight(380)
            ->setDataset([
                ['name' => 'Adultos', 'data' => $adultos],
                ['name' => 'Crianças', 'data' => $criancas],
                ['name' => 'Visitantes', 'data' => $visitantes],
            ])
            ->setColors(['#2563eb', '#f97316', '#22c55e'])
            ->setXAxis($labels)
            ->setToolbar(true)
            ->setGrid();
    }
}
