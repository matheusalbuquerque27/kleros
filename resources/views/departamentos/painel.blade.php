@extends('layouts.main')

@section('title', 'Departamentos')

@section('content')

<div class="container">
    <h1>Departamentos</h1>
    <div class="info">
        <h3>Visão Geral</h3>
        <div class="search-panel">
            @if ($congregacao->config->agrupamentos == 'setor')
                <div class="search-panel-item">
                <label>Setor: </label>
                <select name="setor" id="setor">
                    <option value="setor">Setor 1</option>
                    <option value="setor">Setor 2</option>
                </select>
            </div>
            @endif
            <div class="search-panel-item">
                <label>Membro: </label>
                <input type="text" name="" placeholder="Nome" id="membro">
            </div>
            <div class="search-panel-item">
                <button class="" id="btn_filtrar"><i class="bi bi-search"></i> Procurar</button>
                <button class="" onclick="abrirJanelaModal('{{route('departamentos.form_criar')}}')"><i class="bi bi-plus-circle"></i> Novo</button>
                <button class="" onclick="window.history.back()"><i class="bi bi-arrow-return-left"></i> Voltar</button>
            </div>
        </div>

        @if($departamentos->count()>0)
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
                @foreach ($departamentos as $item)
                <div class="list-item" onclick="abrirJanelaModal('{{route('departamentos.form_editar', $item->id)}}')">
                    <div class="item item-1">
                        <p><i class="bi bi-intersect"></i> {{$item->nome}}</p>                   
                    </div>
                    <div class="item item-2">
                        <p>{{$item->descricao}}</p>
                    </div>
                    <div class="item item-1">
                        <p>{{$item->lider->nome}} @if($item->colider) {{" / " .$item->colider->nome}} @endif </p>
                    </div>                    
                </div><!--list-item-->
                @endforeach
                @if($departamentos->total() > 10)
                    <div class="pagination">
                        {{ $departamentos->links('pagination::default') }}
                    </div>
                @endif
            </div>{{-- content --}} 
        </div>
        @else
            <div class="card">
                <p><i class="bi bi-exclamation-triangle"></i> Ainda não há departamentos para exibição.</p>
            </div>
        @endif
    </div> 
</div>

@endsection