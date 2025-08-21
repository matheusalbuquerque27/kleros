@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')

<div class="container">
    <h1>Editar visitante</h1>
    <form action="{{ route('visitantes.update', $visitante->id)}}" method="post">
        @csrf
        @method('PUT') {{-- Método PUT para atualização --}}

        {{-- Exibe erros gerais, se houver --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-control">
            <div class="form-block">
                <h3>Informações</h3>
                <div class="form-item">
                    <label for="Nome">Nome: </label>
                    <input type="text" name="nome" placeholder="Nome" value="{{ old('nome', $visitante->nome) }}" required>
                </div>
                <div class="form-item">
                    <label for="data_nascimento">Data da visita: </label>
                    <input type="date" name="data_visita" value="{{ old('data_visita', $visitante->data_visita) }}" required>
                </div>
                <div class="form-item">
                    <label for="telefone">Telefone: </label>
                    <input type="text" name="telefone" placeholder="Telefone" value="{{ old('telefone', $visitante->telefone) }}" required>
                </div>
                <div class="form-item">
                    <label for="telefone">Situação do Visitante: </label>
                    <select name="sit_visitante" value="{{ old('sit_visitante', $visitante->sit_visitante->titulo) }}" required>
                        @foreach ($situacao_visitante as $item)
                            <option value="{{$item->id}}">{{$item->titulo}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-item">
                    <label for="telefone">Observações: </label>
                    <textarea name="observacoes" id="" cols="30" rows="10">
                        {{ old('telefone', $visitante->observacoes)}}
                    </textarea>
                </div>
            </div>
            <div class="form-options">
                <button class="btn" type="submit"><i class="bi bi-save"></i> Atualizar Dados</button>
                <button type="button" class="btn" onclick="window.history.back()"><i class="bi bi-x-circle"></i> Cancelar</button>
            </div>
        </div><!--form-control-->
    </form>
</div>

@endsection