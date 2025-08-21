@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')
<div class="container">
    <h1>Bíblia Sagrada - NVI</h1>
    <div class="biblia-container">
        <h2><i class="bi bi-book"></i> {{ $book->name }}</h2>
        <h3>Selecione um capítulo:</h3>

        <div class="grid-list">
            @foreach ($capitulos as $capitulo)
                <a href="{{ route('biblia.verses', [$book->id, $capitulo]) }}" class="grid-item">
                    {{ $capitulo }}
                </a>
            @endforeach
        </div>

        <a href="{{ route('biblia.index') }}" class="btn-voltar">⬅ Voltar para livros</a>
    </div>
</div>
@endsection
