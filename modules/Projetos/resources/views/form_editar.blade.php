@extends('layouts.main')

@section('title', 'Editar Projeto | ' . $appName)

@section('content')
<div class="container">
    <h1>Editar Projeto</h1>
    <div class="info">
        <h3>{{ $projeto->nome }}</h3>
        <form action="{{ route('projetos.update', $projeto) }}" method="post" class="projetos-create__form">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <label>
                    <span>Nome do projeto</span>
                    <input type="text" name="nome" required maxlength="255" value="{{ old('nome', $projeto->nome) }}">
                </label>
                <label>
                    <span>Cor</span>
                    <input type="color" name="cor" value="{{ old('cor', $projeto->cor ?? '#1f2937') }}">
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="para_todos" value="1" @checked(old('para_todos', $projeto->para_todos))>
                    <span>Visível a todos os membros</span>
                </label>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Atualizar</button>
                <a href="{{ route('projetos.exibir', $projeto) }}" class="btn btn-light">Cancelar</a>
            </div>
        </form>

        <form action="{{ route('projetos.delete', $projeto) }}" method="post" class="projetos-delete">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return handleSubmit(event, this.form, 'Deseja realmente remover este projeto?');">
                <i class="bi bi-trash"></i> Excluir projeto
            </button>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .projetos-create__form .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
    }

    .projetos-create__form label {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        font-size: 0.9rem;
    }

    .projetos-create__form input[type="text"],
    .projetos-create__form input[type="color"] {
        border: 1px solid rgba(15, 23, 42, 0.15);
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
    }

    .projetos-create__form .checkbox {
        align-self: flex-end;
        flex-direction: row;
        align-items: center;
        gap: 0.5rem;
    }

    .projetos-create__form .form-actions {
        margin-top: 1rem;
        display: flex;
        gap: 0.75rem;
    }

    .projetos-delete {
        margin-top: 2rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if ($errors->any())
            flashMsg(@json($errors->first()), 'error');
        @endif

        @if (session('success'))
            flashMsg(@json(session('success')), 'success');
        @endif
    });
</script>
@endpush
