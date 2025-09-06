@extends('layouts.main')

@section('title', 'Departamentos')

@section('content')
<div class="page-container">
    <h1 class="page-title">Departamentos</h1>

    {{-- Mensagens --}}
    @if(session('msg'))
        <div class="msg success">
            {{ session('msg') }}
        </div>
    @endif

    {{-- Botão adicionar --}}
    <div class="actions">
        <a href="{{ route('departamentos.create') }}" class="btn primary">+ Novo Departamento</a>
    </div>

    {{-- Lista em cards --}}
    <div class="card-container">
        @forelse($departamentos as $departamento)
            <div class="card">
                <h3>{{ $departamento->titulo }}</h3>
                <p class="sigla">{{ $departamento->sigla }}</p>
                <p>{{ $departamento->descricao }}</p>

                <div class="card-actions">
                    <a href="{{ route('departamentos.edit', $departamento->id) }}" class="btn warning">Editar</a>
                    <form action="{{ route('departamentos.destroy', $departamento->id) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn danger" onclick="return confirm('Deseja excluir este departamento?')">Excluir</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="empty">Nenhum departamento cadastrado.</p>
        @endforelse
    </div>
</div>
@endsection