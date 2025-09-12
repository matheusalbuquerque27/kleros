@extends('layouts.main')

@section('title', 'Departamentos')

@section('content')

<div class="container">
    <h1>Departamentos</h1>
    <div class="info">
        <h3>Visão Geral</h3>
        <div class="search-panel">
            <div class="search-panel-item">
                <button class="" id="btn_filtrar"><i class="bi bi-search"></i> Procurar</button>
                <button class="imprimir"><i class="bi bi-plus-circle"></i> Novo departamento</button>
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
                <div class="item-2">
                    <b>Liderança</b>
                </div>
            </div><!--list-item-->
            <div id="content">
                @foreach ($departamentos as $item)
                <a href="/departamentos/exibir/{{$item->id}}">
                <div class="list-item">
                    <div class="item item-1">
                        <p style="display:flex; align-items: center; gap:.5em"><img src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('storage/images/newuser.png') }}" class="avatar" alt="Avatar">{{$item->nome}}</p>
                    </div>
                    <div class="item item-2">
                        <p>{{$item->descricao}}</p>
                    </div>
                    <div class="item item-1">
                        <p>{{$item->lider}} @if($item->colider) {{" / " .$item->colider}} @endif </p>
                    </div>                    
                </div><!--list-item-->
                </a>
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