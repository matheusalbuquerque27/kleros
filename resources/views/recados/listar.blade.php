@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')

<div class="container">
    <h1>Ver Recados</h1>
    <div class="info">
        <h3>Recados enviados no culto</h3>
        <div class="card-container">
        @if ($recados->isNotEmpty())
            @foreach ($recados as $item)
                <div class="info_item">
                    <p>{{$item->mensagem}}</p>
                    <p class="right"><b>Dono da mensagem</b></p>
                    <p class="tag">Enviado: {{$item->created_at}}</p>
                </div>
            @endforeach
        @else
            <div class="card">
                <p><i class="bi bi-exclamation-triangle"></i> Não há histórico de recados para este culto.</p>  
            </div>
        @endif
            
        </div>
        <div class="form-control">
            <div class="form-options">
                <button type="button" onclick="window.history.back()" class="btn"><i class="bi bi-x-circle"></i> Voltar</button>
            </div>
        </div>
    </div>
</div>

@endsection