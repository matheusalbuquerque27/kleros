@extends('layouts.main')

@section('title', 'AD Jerusalém')

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
    <script src="{{ $chart->cdn() }}"></script>

    {{-- Estilos da toolbar/menu de download --}}
    <style>
        /* barra: fundo suave, cantos e espaçamento */
        .apexcharts-toolbar{
        background: rgba(15,23,42,.06);
        border-radius: 8px;
        padding: 4px;
        gap: 6px;
        }

        /* tamanho dos ícones */
        .apexcharts-toolbar svg {
        width: 18px;
        height: 18px;
        }

        /* cores específicas por ícone */
        .apexcharts-download-icon svg { fill: #2563eb; } /* azul p/ download */
        .apexcharts-zoom-icon svg,
        .apexcharts-zoomin-icon svg,
        .apexcharts-zoomout-icon svg,
        .apexcharts-pan-icon svg,
        .apexcharts-reset-icon svg,
        .apexcharts-menu-icon svg { fill: #334155; }

        /* dropdown do download (PNG/SVG/CSV) */
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
    </style>

    <h1>Painel de Relatórios</h1>
    <div class="info">
        <h3>Gráficos e Estatísticas</h3>

        <p>Visitantes por Mês ({{ $inicio }} a {{ $fim }})</p>
        {!! $chart->container() !!}
        {{-- Removido: {{ $chart->script() }} --}}
    </div>
</div>


@endsection

@push('scripts')
  {{-- 1) Carrega ApexCharts --}}
  <script src="{{ $chart->cdn() }}"></script>
  {{-- 2) Executa o JS do gráfico (depende do ApexCharts acima) --}}
  {{ $chart->script() }}
@endpush

