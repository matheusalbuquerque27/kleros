@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')
<div class="container">
    <h1>Bíblia Sagrada - NVI</h1>
    <div class="biblia-container">
        <h2><i class="bi bi-search"></i> Resultados para: "{{ $query }}"</h2>

        @if ($resultados->isEmpty())
            <p>Nenhum versículo encontrado.</p>
        @else
            <ul>
                @foreach ($resultados as $r)
                    <li>
                        <strong>{{ $r->book }} {{ $r->chapter }}:{{ $r->verse }}</strong> — {{ $r->text }} <a href="{{ route('biblia.verses', [$r->idBook, $r->chapter]) }}" class="link-standard">Abrir Capítulo {{ $r->chapter }}</a>
                    </li>
                @endforeach
            </ul>
        @endif

        <a href="{{ route('biblia.index') }}" class="btn-voltar">⬅ Voltar</a>
    </div>
</div>
@endsection