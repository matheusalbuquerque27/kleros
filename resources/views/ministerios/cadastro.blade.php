@extends('layouts.main')

@section('title', 'Ministérios - AD Jerusalém')

@section('content')

<div class="container">
    <h1>Adicionar Ministério</h1>
    <form action="/ministerios" method="post">
        @csrf
        <div class="form-control">
            <div class="form-item">
                <label for="nome">Nome do ministério: </label>
                <input type="text" name="titulo" placeholder="Nome do grupo">
            </div>
            <div class="form-options">
                <button class="btn" type="submit"><i class="bi bi-plus-circle"></i> Registrar Ministério</button>
                <a href="/cadastros#ministerios"><button type="button" class="btn"><i class="bi bi-x-circle"></i> Cancelar</button></a>
            </div>
        </div>
    </form>
</div>

@endsection