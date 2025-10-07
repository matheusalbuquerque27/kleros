@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')

<div class="container">
    <h1>Quadro de Reuniões</h1>
    <div class="info">
        <h3>Agendadas</h3>
        <div class="nao-imprimir">
            <div class="search-panel">
                <div class="search-panel-item">
                    <label>Assunto: </label>
                    <input type="text" name="" id="nome" placeholder="Assunto">
                </div>
                <div class="search-panel-item">
                    <label>Data: </label>
                    <input type="date" name="" id="data_visita">
                </div>
                <div class="search-panel-item">
                    <button class="" id="btn_filtrar"><i class="bi bi-search"></i> Procurar</button>
                    <button class="" onclick="abrirJanelaModal('{{route('reunioes.form_criar')}}')"><i class="bi bi-plus-circle"></i> Adicionar</button>
                    <button class="" onclick="window.history.back()"><i class="bi bi-arrow-return-left"></i> Voltar</button>
                </div>
            </div>
        </div>
        <div class="list">
            <div class="list-title">
                <div class="item-1">
                    <b>Data</b>
                </div>
                <div class="item-1">
                    <b>Horário</b>
                </div>
                <div class="item-15">
                    <b>Descrição</b>
                </div>
                <div class="item-1">
                    <b>Local</b>
                </div>
                <div class="item-1">
                    <b>Ambiente</b>
                </div>
            </div><!--list-item-->
            <div id="content">
            @foreach ($reunioes as $item)
            <div class="list-item" onclick="abrirJanelaModal('{{ route('reunioes.form_editar', $item->id)}}')">
                <div class="item item-1">
                    <p><i class="bi bi-people-fill"></i> {{ date('d/m/Y', strtotime($item->data_inicio)) }}</p>                
                </div>
                <div class="item item-1">
                    <p class="tag">{{ date('H:i', strtotime($item->data_inicio)) }}</p>
                </div>
                <div class="item item-15">
                    <p>{{ $item->descricao }}</p>
                </div>
                <div class="item item-1">
                    <p>{{ $item->local }}</p>
                </div>
                <div class="item item-1">
                    <p>{{ $item->online ? 'Online' : 'Presencial'}}</p>
                </div>                 
            </div><!--list-item-->
            @endforeach
            @if($reunioes->total() > 10)
                <div class="pagination">
                    {{ $reunioes->links('pagination::default') }}
                </div>
            @endif
            </div><!--content-->
        </div><!--list-->
    </div>
</div>

@endsection