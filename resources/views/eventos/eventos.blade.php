@extends('layouts.main')

@section('title', 'Eventos - AD Jerusalém')

@section('content')

<div class="container">
    <h1>Novo Evento</h1>
    <form action="/eventos" method="post">
        @csrf
        <div class="form-control">
            <div class="form-item">
                <label for="titulo">Título: </label>
                <input type="text" name="titulo" placeholder="Título do evento">
            </div>
            <div class="form-item">
                <label for="grupo_id">Grupo responsável: </label>
                <select name="grupo_id" id="" required>
                    <option value="">Grupo responsável</option>
                    @foreach ($grupos as $item)
                    <option value="{{$item->id}}">{{$item->nome}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-item">
                <label for="data_evento">Data agendada: </label>
                <input type="date" name="data_evento" value="{{ date('Y-m-d') }}">
            </div>
            <div class="form-item">
                <label for="descricao">Descrição: </label>
                <textarea name="descricao" placeholder="Descrição do evento"></textarea>
            </div>
            <div class="form-options">
                <button class="btn" type="submit"><i class="bi bi-plus-circle"></i> Adicionar Evento</button>
                <a href="/cadastros#eventos"><button type="button" class="btn"><i class="bi bi-x-circle"></i> Cancelar</button></a>
            </div>
        </div>
    </form>
</div>

@endsection