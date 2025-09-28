@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . config('app.name'))

@section('content')
<div class="container">
    <div class="nao-imprimir">
        <h1>Painel de Escalas</h1>
        <div class="info">
            <h3>Pesquisar</h3>
            <div class="search-panel">
                <div class="search-panel-item">
                    <label for="tipo">Tipo:</label>
                    <select name="tipo" id="tipo">
                        <option value="">Todas as escalas</option>
                        @foreach($tiposEscala as $tipo)
                            <option value="{{ $tipo->id }}" @selected(($filters['tipo'] ?? '') == $tipo->id)>{{ $tipo->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="search-panel-item">
                    <label for="data_inicio">Data inicial:</label>
                    <input type="date" id="data_inicio" value="{{ $filters['data_inicio'] ?? '' }}">
                </div>
                <div class="search-panel-item">
                    <label for="data_fim">Data final:</label>
                    <input type="date" id="data_fim" value="{{ $filters['data_fim'] ?? '' }}">
                </div>
                <div class="search-panel-item">
                    <button type="button" onclick="abrirJanelaModal('{{ route('escalas.tipos.form_criar') }}')"><i class="bi bi-plus-circle"></i> Novo tipo</button>
                    <button type="button" onclick="abrirJanelaModal('{{ route('escalas.form_criar') }}')"><i class="bi bi-plus-circle"></i> Nova escala</button>
                    <button type="button" class="imprimir"><i class="bi bi-printer"></i> Imprimir</button>
                </div>
            </div>
        </div>
    </div>

    <div id="list" class="list">
        <div class="list-title">
            <div class="item-1"><b>Tipo</b></div>
            <div class="item-1"><b>Data</b></div>
            <div class="item-2"><b>Detalhes</b></div>
        </div>
        <div id="content">
            @include('escalas.includes.lista', ['escalas' => $escalas])
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        function filtrar(event) {
            if (event) {
                event.preventDefault();
            }

            const _token = $('meta[name="csrf-token"]').attr('content');
            const tipo = $('#tipo').val();
            const data_inicio = $('#data_inicio').val();
            const data_fim = $('#data_fim').val();

            $.post('{{ route('escalas.search') }}', { _token, tipo, data_inicio, data_fim })
                .done(function (response) {
                    $('#content').html(response.view);

                    const params = new URLSearchParams();
                    if (tipo) params.set('tipo', tipo);
                    if (data_inicio) params.set('data_inicio', data_inicio);
                    if (data_fim) params.set('data_fim', data_fim);

                    const query = params.toString();
                    const newUrl = query ? `${window.location.pathname}?${query}` : window.location.pathname;
                    window.history.replaceState({}, '', newUrl);
                })
                .fail(function (err) {
                    console.error(err);
                });
        }

        $('#tipo, #data_inicio, #data_fim').on('change', filtrar);
        $('.imprimir').on('click', function (event) {
            event.preventDefault();
            window.print();
        });
    });
</script>
@endpush
