<?php

function formatarData($data, $formato = 'd/m/Y') {
    if ($data) {
        return date($formato, strtotime($data));
    }
    return null;
}

function pegarDiasDeIntervaloDatas(string $startDate, string $endDate): array
{
    $dates = [];
    $currentDate = new DateTime($startDate);
    $endDateObj = new DateTime($endDate);

    // Ajusta a data final para incluir o último dia
    $endDateObj->modify('+1 day');

    // Intervalo de um dia
    $interval = new DateInterval('P1D'); // P1D significa "Período de 1 Dia"

    // Cria um DatePeriod que itera de $currentDate até $endDateObj com um intervalo de 1 dia
    $period = new DatePeriod($currentDate, $interval, $endDateObj);

    foreach ($period as $date) {
        $dates[] = $date->format('Y-m-d'); // Formata a data como 'YYYY-MM-DD'
    }

    return $dates;
}


?>