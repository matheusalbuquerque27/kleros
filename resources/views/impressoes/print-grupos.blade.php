@extends('layouts.print')

@section('content')

<section>
    @foreach ($membrosByGrupo as $nomeGrupo => $membros)
        
        @if (count($membros) > 0)
            <h2>{{ $nomeGrupo }}</h2>
            <hr>

            @foreach ($membros as $membro)
            <p>{{ $membro->nome }} - {{ $membro->cargo }}</p>
            @endforeach
        @endif
        
    @endforeach
</section>
    
@endsection