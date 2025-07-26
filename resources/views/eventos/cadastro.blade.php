@extends('layouts.main')

@section('title', 'Eventos - AD Jerusalém')

@section('content')

@if($errors->all())
<div class="msg">
    <div class="error">
        <ul>
            {{$errors->first()}}
        </ul>
    </div>
</div>
@endif

<div class="container">
    <h1>Novo Evento</h1>
    <form action="/eventos" method="post">
        @csrf
        <div class="form-control">
            <div class="form-item">
                <label for="titulo">Título: </label>
                <input type="text" name="titulo" id="titulo" placeholder="Título do evento">
            </div>
            <div class="form-item">
                <label for="grupo_id">Grupo responsável: </label>
                <select name="grupo_id" id="grupo_id" required>
                    <option value="">Grupo responsável</option>
                    @foreach ($grupos as $item)
                    <option value="{{$item->id}}">{{$item->nome}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-item">
                <label for="data_inicio">Data de início: </label>
                <input type="date" name="data_inicio" id="data_inicio" value="{{ date('Y-m-d') }}">
            </div>
            <div class="form-item">
                <label for="data_encerramento">Data de encerramento: </label>
                <input type="date" name="data_encerramento" id="data_encerramento" value="{{ date('Y-m-d') }}">
            </div>
            <div class="form-item">
                <label for="descricao">Descrição: </label>
                <textarea name="descricao" placeholder="Descrição do evento"></textarea>
            </div>
            <div class="form-item">
                <label for="geracao_cultos">Geração de cultos: </label>
                <div class="form-square">
                    <div>
                        <input type="radio" id="automatica" name="geracao_cultos" value="1" checked>
                        <label for="automatica">Automática</label>
                    </div>
                    <div>
                        <input type="radio" id="manual" name="geracao_cultos" value="0">
                        <label for="manual">Manual</label>
                    </div>
                </div>
            </div>
            <div class="form-options">
                <button class="btn" type="submit"><i class="bi bi-plus-circle"></i> Adicionar Evento</button>
                <a href="/cadastros#eventos"><button type="button" class="btn"><i class="bi bi-x-circle"></i> Cancelar</button></a>
            </div>
        </div>
    </form>
</div>

@endsection