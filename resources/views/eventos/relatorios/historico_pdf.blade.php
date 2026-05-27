<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Histórico de Eventos</title>
    <style>
        @page {
            margin: 24mm 18mm 22mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
            line-height: 1.45;
            margin: 0;
        }

        .header {
            display: table;
            width: 100%;
            border-bottom: 2px solid #111827;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }

        .header-copy,
        .header-logo {
            display: table-cell;
            vertical-align: top;
        }

        .header-copy {
            width: 76%;
            padding-right: 16px;
        }

        .header-logo {
            width: 24%;
            text-align: right;
        }

        .header-logo img {
            max-height: 110px;
            object-fit: contain;
        }

        .header h1 {
            margin: 0 0 6px;
            font-size: 24px;
        }

        .header p {
            margin: 2px 0;
            color: #4b5563;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .summary td {
            width: 25%;
            border: 1px solid #d1d5db;
            padding: 10px;
            vertical-align: top;
        }

        .summary strong {
            display: block;
            margin-bottom: 4px;
            font-size: 11px;
            text-transform: uppercase;
            color: #6b7280;
        }

        .summary span {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        .event-card {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 5px 6px;
            margin-bottom: 8px;
            page-break-inside: avoid;
        }

        .event-header h2 {
            margin: 0 0 1px;
            font-size: 13px;
        }

        .event-header p,
        .occurrence-list li {
            margin: 0;
            color: #4b5563;
            font-size: 11px;
        }

        .grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        .grid td {
            width: 50%;
            padding: 3px 4px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
            font-size: 11px;
        }

        .label {
            display: block;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            color: #6b7280;
        }

        .occurrence-list {
            margin: 4px 0 0 16px;
            padding: 0;
        }

        .empty {
            padding: 18px;
            border: 1px solid #d1d5db;
            color: #4b5563;
        }

        .footer {
            margin-top: 16px;
            font-size: 10px;
            color: #6b7280;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-copy">
            <h1>Histórico de Eventos</h1>
            <p><strong>Congregação:</strong> {{ $congregacao->nome ?? $congregacao->nome_curto ?? 'Congregação' }}</p>
            <p><strong>Período:</strong> {{ $periodo }}</p>
            <p><strong>Gerado em:</strong> {{ $geradoEm->format('d/m/Y H:i') }}</p>
        </div>
        <div class="header-logo">
            @if (!empty($logoDataUri))
                <img src="{{ $logoDataUri }}" alt="Logo da congregação">
            @endif
        </div>
    </div>

    <table class="summary">
        <tr>
            <td><strong>Total de eventos</strong><span>{{ $resumo['total_eventos'] }}</span></td>
            <td><strong>Total de ocorrências</strong><span>{{ $resumo['total_ocorrencias'] }}</span></td>
            <td><strong>Com inscrição</strong><span>{{ $resumo['com_inscricao'] }}</span></td>
            <td><strong>Vinculados a grupo</strong><span>{{ $resumo['com_grupo'] }}</span></td>
        </tr>
    </table>

    @forelse ($eventos as $evento)
        <section class="event-card">
            <div class="event-header">
                <h2>{{ $evento->titulo }}</h2>
                <p>
                    {{ $evento->data_inicio ? \Carbon\Carbon::parse($evento->data_inicio)->format('d/m/Y') : 'Sem data' }}
                    @if ($evento->data_encerramento && $evento->data_encerramento !== $evento->data_inicio)
                        até {{ \Carbon\Carbon::parse($evento->data_encerramento)->format('d/m/Y') }}
                    @endif
                </p>
            </div>

            <table class="grid">
                <tr>
                    <td>
                        <span class="label">Grupo responsável</span>
                        {{ $evento->grupo_label }}
                    </td>
                    <td>
                        <span class="label">Requer inscrição</span>
                        {{ $evento->inscricoes_label }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="label">Local</span>
                        {{ $evento->local ?: 'Não informado' }}
                    </td>
                    <td>
                        <span class="label">Ocorrências</span>
                        {{ $evento->ocorrencias_total }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span class="label">Descrição</span>
                        {{ $evento->descricao ?: 'Sem descrição.' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span class="label">Lista de ocorrências</span>
                        @if ($evento->ocorrencias->isNotEmpty())
                            <ul class="occurrence-list">
                                @foreach ($evento->ocorrencias as $ocorrencia)
                                    <li>
                                        {{ \Carbon\Carbon::parse($ocorrencia->data_ocorrencia)->format('d/m/Y') }}
                                        @if ($ocorrencia->horario_inicio)
                                            às {{ substr($ocorrencia->horario_inicio, 0, 5) }}
                                        @endif
                                        @if ($ocorrencia->local)
                                            | {{ $ocorrencia->local }}
                                        @endif
                                        @if ($ocorrencia->descricao)
                                            | {{ $ocorrencia->descricao }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            Nenhuma ocorrência registrada.
                        @endif
                    </td>
                </tr>
            </table>
        </section>
    @empty
        <div class="empty">Nenhum evento foi encontrado para os filtros selecionados.</div>
    @endforelse

    <div class="footer">Relatório gerado automaticamente pelo Kleros.</div>
</body>
</html>
