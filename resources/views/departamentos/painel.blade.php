@extends('layouts.main')

@section('title', 'Departamentos')

@section('content')

<div class="container">
    <h1>Departamentos</h1>
    <div class="info">
        <h3>Visão Geral</h3>
        @php
            $setores = isset($setores) ? $setores : collect();
        @endphp
        <div class="search-panel">
            @if ($congregacao->config->agrupamentos == 'setor')
                <div class="search-panel-item">
                    <label for="setor">Setor: </label>
                    <select name="setor" id="setor">
                        <option value="">Todos os setores</option>
                        @forelse($setores as $setor)
                            <option value="{{ $setor->id }}">{{ $setor->nome }}</option>
                        @empty
                            <option value="" disabled>Nenhum setor cadastrado</option>
                        @endforelse
                    </select>
                    <button type="button" class="" onclick="abrirJanelaModal('{{ route('setores.form_criar') }}')"><i class="bi bi-plus-circle"></i> Novo setor</button>
                </div>
            @endif
            <div class="search-panel-item">
                <label>Membro: </label>
                <input type="text" name="" placeholder="Nome" id="membro">
            </div>
            <div class="search-panel-item">
                <button class="" id="btn_filtrar"><i class="bi bi-search"></i> Procurar</button>
                <button class="" onclick="abrirJanelaModal('{{route('departamentos.form_criar')}}')"><i class="bi bi-plus-circle"></i> Adicionar</button>
                <button class="" onclick="window.history.back()"><i class="bi bi-arrow-return-left"></i> Voltar</button>
            </div>
        </div>

        <div id="list" class="list">
            <div class="list-title">
                <div class="item-1">
                    <b>Nome</b>
                </div>
                <div class="item-2">
                    <b>Descrição</b>
                </div>
                <div class="item-1">
                    <b>Liderança</b>
                </div>
            </div><!--list-item-->
            <div id="content">
                @include('departamentos.includes.lista', ['departamentos' => $departamentos])
            </div>{{-- content --}} 
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
            const setor = $('#setor').length ? $('#setor').val() : null;
            const membro = $('#membro').val();

            $.post('{{ route('departamentos.search') }}', { _token, setor, membro })
                .done(function (response) {
                    $('#content').html(response.view);
                })
                .fail(function (err) {
                    console.error(err);
                });
        }

        $('#btn_filtrar').on('click', filtrar);

        const $setor = $('#setor');
        if ($setor.length) {
            $setor.on('change', function () {
                filtrar();
            });
        }
    });
</script>
@endpush
