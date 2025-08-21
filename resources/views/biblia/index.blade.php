@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')
<div class="container">
    <h1>Biblia Sagrada | NVI</h1>
    <div class="info">
        <h3>Leitura Bíblica</h3>
        <div class="biblia-container">
            
        {{-- Busca --}}
        <form action="{{ route('biblia.search') }}" method="GET" class="search-form">
            <input type="text" name="q" placeholder="Buscar palavra ou frase" required>
            <button type="submit"><i class="bi bi-search"></i> Buscar</button>
        </form>

        {{-- Listagem por testamento --}}
        @foreach ($testamentos as $testamento)
            <div class="testamento">
                <h2>{{ $testamento->name }}</h2>
                <div class="livros-grid">
                    @foreach ($testamento->books as $book)
                        <a href="{{ route('biblia.chapters', $book->id) }}" class="livro-card">
                            {{ $book->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
        </div>
    </div>
</div>
@endsection