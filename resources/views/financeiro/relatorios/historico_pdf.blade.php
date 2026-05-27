<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório Financeiro</title>
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

        .date-group {
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .date-group h2 {
            margin: 0 0 6px;
            font-size: 14px;
            color: #111827;
        }

        .entry-card {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 4px 5px;
            margin-bottom: 5px;
            page-break-inside: avoid;
        }

        .grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2px;
        }

        .grid td {
            width: 50%;
            padding: 2px 4px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
            font-size: 11px;
            line-height: 1.2;
        }

        .label {
            display: block;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            color: #6b7280;
            line-height: 1.1;
        }

        .value-positive {
            color: #166534;
            font-weight: 700;
        }

        .value-negative {
            color: #b91c1c;
            font-weight: 700;
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
            <h1>Relatório Financeiro</h1>
            <p><strong>Congregação:</strong> {{ $congregacao->nome ?? $congregacao->nome_curto ?? 'Congregação' }}</p>
            <p><strong>Período:</strong> {{ $periodo }}</p>
            @foreach ($filtros as $label => $valor)
                <p><strong>{{ $label }}:</strong> {{ $valor }}</p>
            @endforeach
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
            <td><strong>Total de lançamentos</strong><span>{{ $resumo['total_lancamentos'] }}</span></td>
            <td><strong>Total de entradas</strong><span>R$ {{ number_format($resumo['total_entradas'], 2, ',', '.') }}</span></td>
            <td><strong>Total de saídas</strong><span>R$ {{ number_format($resumo['total_saidas'], 2, ',', '.') }}</span></td>
            <td><strong>Saldo líquido</strong><span>R$ {{ number_format($resumo['saldo_liquido'], 2, ',', '.') }}</span></td>
        </tr>
    </table>

    @php
        $lancamentosPorData = $lancamentos->groupBy(function ($lancamento) {
            return optional($lancamento->data_lancamento)->format('Y-m-d') ?? 'sem-data';
        });
    @endphp

    @forelse ($lancamentosPorData as $data => $lancamentosDoDia)
        <div class="date-group">
            <h2>{{ $data === 'sem-data' ? 'Data não informada' : \Carbon\Carbon::parse($data)->format('d/m/Y') }}</h2>

            @foreach ($lancamentosDoDia as $lancamento)
                <section class="entry-card">
                    <table class="grid">
                        <tr>
                            <td>
                                <span class="label">Caixa</span>
                                {{ optional($lancamento->caixa)->nome ?: 'Não informado' }}
                            </td>
                            <td>
                                <span class="label">Tipo</span>
                                {{ ucfirst($lancamento->tipo) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="label">Categoria</span>
                                {{ optional($lancamento->tipoLancamento)->nome ?: 'Não informada' }}
                            </td>
                            <td>
                                <span class="label">Valor</span>
                                <span class="{{ $lancamento->tipo === 'entrada' ? 'value-positive' : 'value-negative' }}">
                                    R$ {{ number_format((float) $lancamento->valor, 2, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span class="label">Descrição</span>
                                {{ $lancamento->descricao ?: 'Sem descrição.' }}
                            </td>
                        </tr>
                    </table>
                </section>
            @endforeach
        </div>
    @empty
        <div class="empty">Nenhum lançamento foi encontrado para os filtros selecionados.</div>
    @endforelse

    <div class="footer">Relatório gerado automaticamente pelo Kleros.</div>
</body>
</html>
