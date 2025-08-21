@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')

<div class="container">
    <h1>Histórico de Recados</h1>
    <div class="info">
        <h3>Últimos recados</h3>
    
        <form action="/recados" method="post">
            @csrf
            <div class="form-control">
                <div class="form-item">
                    <label for="mensagem">Mensagem: </label>
                    <textarea type="date" rows="10" name="mensagem" placeholder="Escreva aqui seu recado..."></textarea>
                </div>
                <div class="form-options">
                    <button class="btn" type="submit"><i class="bi bi-plus-circle"></i> Registrar Recado</button>
                    <button type="button" class="btn"><i class="bi bi-x-circle"></i> Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection