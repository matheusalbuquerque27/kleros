@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')

<div class="container">
    <h2>Membros com ministério de: </h2>
    <h1>{{$ministerio->titulo}}</h1>
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
        <div class="form-control">
            <div class="form-options">
                <a href="/ministerios/adicionar"><button class="btn mg-top-10"><i class="bi bi-plus-circle"></i> Novo ministério</button></a>
                <button class="btn mg-top-10"><i class="bi bi-printer"></i> Imprimir lista</button>
                <a href="/cadastros#ministerios"><button class="btn mg-top-10"><i class="bi bi-arrow-return-left"></i> Voltar</button></a>    
            </div>
        </div>
</div>

@endsection