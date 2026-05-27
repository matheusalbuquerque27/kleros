<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Membros</title>
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

        .member-card {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 4px 5px;
            margin-bottom: 5px;
            page-break-inside: avoid;
        }

        .member-header h2 {
            margin: 0 0 1px;
            font-size: 13px;
            line-height: 1.2;
        }

        .member-header p {
            margin: 0 0 3px;
            color: #4b5563;
            font-size: 11px;
            line-height: 1.2;
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
            <h1>Relatório de Membros</h1>
            <p><strong>Congregação:</strong> {{ $congregacao->nome ?? $congregacao->nome_curto ?? 'Congregação' }}</p>
            <p><strong>Contexto:</strong> {{ $contexto }}</p>
            @if (!empty($keyword))
                <p><strong>Filtro {{ $filtroLabel }}:</strong> {{ $keyword }}</p>
            @endif
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
            <td><strong>Total de membros</strong><span>{{ $resumo['total_membros'] }}</span></td>
            <td><strong>Com telefone</strong><span>{{ $resumo['com_telefone'] }}</span></td>
            <td><strong>Com e-mail</strong><span>{{ $resumo['com_email'] }}</span></td>
            <td><strong>Com ministério</strong><span>{{ $resumo['com_ministerio'] }}</span></td>
        </tr>
    </table>

    @forelse ($membros as $membro)
        <section class="member-card">
            <div class="member-header">
                <h2>{{ $membro->nome }}</h2>
                <p>{{ $showInactives ? 'Membro inativo' : 'Membro ativo' }}</p>
            </div>

            <table class="grid">
                <tr>
                    <td>
                        <span class="label">Telefone</span>
                        {{ $membro->telefone ?: 'Não informado' }}
                    </td>
                    <td>
                        <span class="label">E-mail</span>
                        {{ $membro->email ?: 'Não informado' }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="label">Ministério</span>
                        {{ optional($membro->ministerio)->titulo ?: 'Não informado' }}
                    </td>
                    <td>
                        <span class="label">Data de nascimento</span>
                        {{ $membro->data_nascimento ? $membro->data_nascimento->format('d/m/Y') : 'Não informada' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span class="label">Endereço</span>
                        {{ trim(collect([$membro->endereco, $membro->numero, $membro->bairro])->filter()->implode(', ')) ?: 'Não informado' }}
                    </td>
                </tr>
            </table>
        </section>
    @empty
        <div class="empty">Nenhum membro foi encontrado para os filtros selecionados.</div>
    @endforelse

    <div class="footer">Relatório gerado automaticamente pelo Kleros.</div>
</body>
</html>
