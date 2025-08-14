@extends('layouts.main')

@section('title', 'AD Jerusalém')

@section('content')

<div class="container">
    <h1>Quadro de Reuniões</h1>
    <div class="info">
        <h3>Agendadas</h3>
        <div class="list">
            <div class="list-title">
                <div class="item-2">
                    <b>Data</b>
                </div>
                <div class="item-1">
                    <b>Horário</b>
                </div>
                <div class="item-2">
                    <b>Local</b>
                </div>
                <div class="item-1">
                    <b>Descrição</b>
                </div>
            </div><!--list-item-->
            <div id="content">
                @foreach ($reunioes as $item)
            <a href="#">
            <div class="list-item">
                <div class="item item-2">
                    <p>{{ date('d/m/Y', strtotime($item->data)) }}</p>                </div>
                <div class="item item-1">
                    <p>{{ date('H:i', strtotime($item->horario)) }}</p>
                </div>
                <div class="item item-2">
                    <p>{{ $item->local }}</p>
                </div>
                <div class="item item-1">
                    <p>{{ $item->descricao }}</p>
                </div>
                
            </div><!--list-item-->
            </a>
            @endforeach
            </div><!--content-->
        </div><!--list-->
    </div>
</div>

@endsection