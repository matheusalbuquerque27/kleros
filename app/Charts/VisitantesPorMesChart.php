<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class VisitantesPorMesChart
{
    public function build(array $labels, array $data)
    {
        // Sanear + reindexar
        $labels = array_values(array_map('strval', $labels));
        $data   = array_values(array_map('intval', $data));

        // Alinhar tamanhos
        $n = min(count($labels), count($data));
        $labels = array_slice($labels, 0, $n);
        $data   = array_slice($data, 0, $n);

        return (new LarapexChart)
            ->barChart()
            ->setHeight(380) // dá espaço p/ toolbar
            ->setDataset([['name' => 'Visitantes', 'data' => $data]])
            ->setXAxis($labels)
            ->setToolbar(true)
            ->setGrid();
    }
}