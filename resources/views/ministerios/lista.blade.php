@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')

<div class="container">
    <h1>Ministério de: {{$ministerio->titulo}}</h1>
    <div class="info">
        <h3>Membros</h3>
        <div class="search-panel">
            <div class="search-panel-item">
                    <label>Buscar: </label>
                    <input type="text" name="" placeholder="Nome" id="chave">
                    <a href="/ministerios/adicionar"><button><i class="bi bi-search"></i> Procurar</button></a>
                </div>
            <div class="search-panel-item">
                <label>Outros membros: </label>
                <select name="membro" id="nome" placeholder="Outros membros">
                    @if ($membros != null)
                        @foreach ($membros as $item)
                            <option value="{{$item->id}}">{{$item->nome}}</option>
                        @endforeach
                    @endif                        
                </select>
            </div>
            <div class="search-panel-item">
                <a href="/ministerios/adicionar"><button><i class="bi bi-plus-circle"></i> Incluir</button></a>
                <button><i class="bi bi-printer"></i> Imprimir</button>
                <a href="/cadastros#ministerios"><button><i class="bi bi-arrow-return-left"></i> Voltar</button></a>    
            </div>
        </div>
    </div>
    <div class="list">
        <div class="list-title">
            <div class="item-1">
                <b>Nome</b>
            </div>
            <div class="item-1">
                <b>Telefone</b>
            </div>
            <div class="item-2">
                <b>Endereço</b>
            </div>
            <div class="item-1">
                <b>Ministério</b>
            </div>
        </div><!--list-item-->
        @foreach ($membros as $item)
        <div class="list-item">
            <div class="item item-1">
                <p>{{$item->nome}}</p>
            </div>
            <div class="item item-1">
                <p>{{$item->telefone}}</p>
            </div>
            <div class="item item-2">
                <p>{{$item->endereco}}, {{$item->numero}} - {{$item->bairro}}</p>
            </div>
            <div class="item item-1">
                <p>{{$item->ministerio->titulo}}</p>
            </div>
        </div><!--list-item-->
        @endforeach
        @if($membros->total() > 10)
            <div class="pagination">
                {{ $membros->links('pagination::default') }}
            </div>
        @endif
</div>

@endsection