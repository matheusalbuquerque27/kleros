@extends('layouts.main')

@section('title', 'Visitantes - AD Jerusalém')

@section('content')

@if ($errors->any())
    <div class="msg">
        <div class="error">
            {{ $errors->first() }}
        </div>
    </div>
@endif

<div class="container">
    <h1>Cadastrar Visitante</h1>
    <form action="/visitantes" method="post">
        @csrf
        <div class="form-control">
            <div class="form-item">
                <label for="Nome">Nome: </label>
                <input type="text" name="nome" placeholder="Nome completo" required>
            </div>
            <div class="form-item">
                <label for="telefone">Telefone/Celular: </label>
                <input type="tel" name="telefone" id="telefone" placeholder="(00)00000-0000" required>
            </div>
            <div class="form-item">
                <label for="data_visita">Data de visita: </label>
                <input type="date" name="data_visita" value="{{date("Y-m-d")}}" placeholder="Data de visita" required>
            </div>
            <div class="form-item">
                <label for="situacao">Situação: </label>
                <select name="situacao" id="">
                    <option value="">Situação do visitante: </option>
                    @foreach ($situacao_visitante as $item)
                    <option value="{{$item->id}}">{{$item->titulo}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-item">
                <label for="observacoes">Observações: </label>
                <textarea name="observacoes" placeholder="Observações importantes"></textarea>
            </div>
            <div class="form-options">
                <button class="btn mg-top-10" type="submit"><i class="bi bi-plus-circle"></i> Salvar Dados</button>
                <a href="/visitantes/historico"><button class="btn mg-top-10" type="button"><i class="bi bi-card-list"></i> Histórico</button></a>
                <button type="button" onclick="window.history.back()" class="btn mg-top-10"><i class="bi bi-x-circle"></i> Cancelar</button></a>
            </div>
        </div>
    </form>
    <a href="/recados/adicionar"><button type="button" class="float-btn"><i class="bi bi-chat-left-dots"></i></button></a>
    <div class="clear"></div>
</div>

@endsection