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

function primeiroEUltimoNome(string $nomeCompleto): string {
    // quebra o nome em partes, removendo espaços extras
    $partes = explode(' ', trim($nomeCompleto));

    // se só tem um nome, retorna ele mesmo
    if (count($partes) === 1) {
        return $partes[0];
    }

    // monta a string com primeiro e último
    return $partes[0] . ' ' . $partes[count($partes) - 1];
}

function diaSemana($numero)
{
    $dias = [
        1 => 'Domingo',
        2 => 'Segunda-feira',
        3 => 'Terça-feira',
        4 => 'Quarta-feira',
        5 => 'Quinta-feira',
        6 => 'Sexta-feira',
        7 => 'Sábado',
    ];

    return $dias[$numero] ?? 'Não definido';
}


if (! function_exists('module_enabled')) {
    function module_enabled(string $module): bool
    {
        static $cache;

        $module = strtolower($module);

        if ($cache !== null && array_key_exists($module, $cache)) {
            return $cache[$module];
        }

        $cache ??= [];

        $modulesPath = base_path('modules');
        $manifestPath = $modulesPath . '/' . ucfirst($module) . '/module.json';
        $enabled = false;

        if (file_exists($manifestPath)) {
            $definition = json_decode(file_get_contents($manifestPath), true) ?: [];
            $enabled = (bool) ($definition['enabled'] ?? false);
        }

        $congregacaoId = app()->bound('congregacao') ? optional(app('congregacao'))->id : null;

        if ($congregacaoId && \Illuminate\Support\Facades\Schema::hasTable('extensoes')) {
            $record = App\Models\Extensao::query()
                ->where('congregacao_id', $congregacaoId)
                ->where('module', $module)
                ->first();

            if ($record) {
                $enabled = (bool) $record->enabled;
            }
        }

        return $cache[$module] = $enabled;
    }
}


if (! function_exists('member_activity_log')) {
    function member_activity_log(string $action, array $context = []): \App\Models\MemberActivityLog
    {
        return app(\App\Services\MemberActivityLogger::class)->log($action, $context);
    }
}


?>
