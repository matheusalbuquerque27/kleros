@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')
@if($errors->all())
<div class="msg">
    <div class="error">
        <ul>
            {{$errors->first()}}
        </ul>
    </div>
</div>
@endif

<div class="container">

    {{-- Estilos da toolbar/menu de download --}}
    <style>
    /* === Estilos da toolbar que você já tinha === */
    .apexcharts-toolbar {
        background: rgba(15,23,42,.06);
        border-radius: 8px;
        padding: 4px;
        gap: 6px;
    }

    .apexcharts-toolbar svg {
        width: 18px;
        height: 18px;
    }

    .apexcharts-download-icon svg { fill: #2563eb; }
    .apexcharts-zoom-icon svg,
    .apexcharts-zoomin-icon svg,
    .apexcharts-zoomout-icon svg,
    .apexcharts-pan-icon svg,
    .apexcharts-reset-icon svg,
    .apexcharts-menu-icon svg { fill: #334155; }

    .apexcharts-menu {
        border-radius: 8px !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 10px 20px rgba(2,6,23,.12) !important;
        overflow: hidden;
    }
    .apexcharts-menu-item {
        font-size: 14px !important;
        padding: 8px 12px !important;
        color: #0f172a !important;
    }
    .apexcharts-menu-item:hover {
        background: #f1f5f9 !important;
    }

    /* === Layout para os gráficos lado a lado === */
    .charts-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-top: 20px;
    }

    @media (max-width: 768px) {
        .charts-container {
            grid-template-columns: 1fr; /* empilha no mobile */
        }
    }

    .chart-box {
        background: #fff;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .chart-box h2 {
        font-size: 1.25rem;
        margin-bottom: 12px;
        color: #1e293b;
    }
    </style>

    <h1>Painel de Relatórios</h1>
    <div class="info">
        <h3>Gráficos e Estatísticas</h3>

        {{-- <div class="search-panel">
            <form method="POST" action="{{ route('relatorios.painel') }}" class="nao-imprimir" style="margin-bottom:1rem">
                <div class="search-panel-item">
                    <label>De:</label>
                    <input type="month" name="inicio_mes" value="" required>
                    <label>Até:</label>
                    <input type="month" name="fim_mes" value="" required>
                    <button type="button" class="btn-filter" id="visitante_mes"><i class="bi bi-search"></i> Filtrar</button>
                    <button type="button"><i class="bi bi-eraser"></i> Limpar</button>
                </div>
            </form>
        </div> --}}

        {{-- Gráfico de Visitantes por Mês --}}
        <div class="charts-container">
            <div class="chart-box">
                <h2>Visitantes por Mês</h2>
                {!! $chartVisitantes->container() !!}
            </div>

            <div class="chart-box">
                <h2>Membros por Sexo</h2>
                {!! $chartMembros->container() !!}
            </div>

            <div class="chart-box">
                <h2>Membros por Faixa Etária</h2>
                {!! $chartFaixaEtaria->container() !!}
            </div>

            <div class="chart-box">
                <h2>Frequência nos Cultos (Últimos 6)</h2>
                {!! $chartFrequenciaCultos->container() !!}
            </div>

            <div class="chart-box">
                <h2>Fluxo de Frequência</h2>
                {!! $chartFluxoCultos->container() !!}
            </div>

            <div class="chart-box">
                <h2>Lançamentos Financeiros</h2>
                {!! $chartLancamentos->container() !!}
            </div> <!-- Para deixar ocupar 100% style="grid-column: 1 / -1;" -->
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script src="{{ $chartVisitantes->cdn() }}"></script>
{{ $chartVisitantes->script() }}
{{ $chartMembros->script() }}
{{ $chartFaixaEtaria->script() }}
{{ $chartFrequenciaCultos->script() }}
{{ $chartFluxoCultos->script() }}
{{ $chartLancamentos->script() }}
@endpush
