@extends('layouts.print')

@section('content')

<section>
    @foreach ($membrosByMinisterio as $nomeMinisterio => $membros)
        
        @if (count($membros) > 0)
            <h3>{{ $nomeMinisterio }}</h3>
            <hr>

            @foreach ($membros as $membro)
            <div class="membroContent">
                <p><b>Nome:</b> {{ $membro->nome }} - <b>Contato:</b> {{$membro->telefone}} </p>
                <p><b>RG:</b> {{ $membro->rg }} - <b>CPF:</b> {{$membro->cpf }} - <b>Data de nascimento:</b> {{formatarData($membro->data_nascimento)}} </p>
                <p><b>Estado civil:</b> {{$membro->estadoCiv->titulo}} - <b>Escolaridade:</b> {{$membro->escolaridade->titulo}} - <b>Profissão:</b> {{$membro->profissao}}</p>
            </div>
            @endforeach
        @endif
        
    @endforeach
</section>
    
@endsection