@extends('layouts.main')

@section('title', 'Integrantes - AD Jerusalém')

@section('content')

<div class="container">
    <h2>Integrantes do grupo: </h2>
    <h1>{{$grupo->nome}}</h1>
    <div class="info">
        <h3>Adicionar membro</h3>
        <form action="/grupos/integrantes" method="post">
            @csrf
            <div class="search-panel">
                <div class="search-panel-item">
                    <label>Nome do membro: </label>
                    <select name="membro" id="nome" placeholder="Nome do membro">
                        @foreach ($membros as $item)
                        <option value="{{$item->id}}">{{$item->nome}}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="grupo" value="{{$grupo->id}}">
                </div>
                <div class="form-control">
                    <button type="submit" id="btn_filtrar"><i class="bi bi-search"></i> Incluir</button>
                    <a href="/cadastros#visitantes"><button type="button" class="" id="btn_filtrar"><i class="bi bi-printer"></i> Imprimir</button></a>
                    <a href="/cadastros#grupos"><button type="button" class="" onclick="window.history.back()" id="btn_filtrar"><i class="bi bi-arrow-return-left"></i> Voltar</button></a>
                </div>
            </div>
        </form>
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
        @foreach ($integrantes as $item)
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
    </div>
</div>

@endsection