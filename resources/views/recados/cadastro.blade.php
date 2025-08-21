@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')

<div class="container">
    <h1>Escrever Recado</h1>
    <div class="info">
        <h3>Edição</h3>
        <form action="/recados" method="post">
            @csrf
            
            <div class="form-control">
                <div class="form-item">
                    <label for="mensagem">Mensagem: </label>
                    <textarea type="date" rows="10" name="mensagem" placeholder="Escreva aqui seu recado..."></textarea>
                </div>
                <div class="form-options">
                    <button class="btn" type="submit"><i class="bi bi-plus-circle"></i> Registrar Recado</button>
                    <a href="/visitantes/adicionar"><button type="button" class="btn"><i class="bi bi-x-circle"></i> Cancelar</button></a>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection