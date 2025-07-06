@extends('layouts.main')

@section('title', 'Editar Membro - AD Jerusalém')

@section('content')

<div class="container">
    <h1>Editar membro</h1>
    <form action="{{ route('membros.atualizar', $membro->id)}}" method="post">
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
                <h3>Informações básicas</h3>
                <div class="form-item">
                    <label for="Nome">Nome: </label>
                    <input type="text" name="nome" placeholder="Nome completo" value="{{ old('nome', $membro->nome) }}" required>
                    {{-- Exibe erro específico para o campo 'nome' --}}
                    @error('nome')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-item">
                    <label for="rg">RG: </label>
                    <input type="text" name="rg" placeholder="RG" value="{{ old('rg', $membro->rg) }}">
                </div>
                <div class="form-item">
                    <label for="cpf">CPF: </label>
                    <input type="text" name="cpf" placeholder="CPF" value="{{ old('cpf', $membro->cpf) }}" required>
                </div>
                <div class="form-item">
                    <label for="data_nascimento">Data de nascimento: </label>
                    <input type="date" name="data_nascimento" value="{{ old('data_nascimento', $membro->data_nascimento) }}" required>
                </div>
                <div class="form-item">
                    <label for="telefone">Telefone: </label>
                    <input type="text" name="telefone" placeholder="Telefone" value="{{ old('telefone', $membro->telefone) }}" required>
                </div>
                <div class="form-item">
                    <label for="estado_civil">Estado civil: </label>
                    <select name="estado_civil" id="">
                        @foreach ($estado_civil as $item)
                            <option value="{{$item->id}}" {{ old('estado_civil', $membro->estado_civil) == $item->id ? 'selected' : '' }}>
                                {{$item->titulo}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-item">
                    <label for="escolaridade">Escolaridade: </label>
                    <select name="escolaridade" id="">
                        @foreach ($escolaridade as $item)
                            <option value="{{$item->id}}" {{ old('escolaridade', $membro->escolaridade) == $item->id ? 'selected' : '' }}>
                                {{$item->titulo}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-item">
                    <label for="profissao">Profissão: </label>
                    <input type="text" name="profissao" placeholder="Profissão">
                </div>
            </div>
            <div class="form-block">
                <h3>Informações de endereço</h3>
                <div class="form-item">
                    <label for="endereco">Endereço: </label>
                    <input type="text" name="endereco" placeholder="Endereço" value="{{ old('endereco', $membro->endereco) }}">
                </div>
                <div class="form-item">
                    <label for="numero">Número: </label>
                    <input type="text" name="numero" placeholder="Número" value="{{ old('numero', $membro->numero) }}">
                </div>
                <div class="form-item">
                    <label for="bairro">Bairro: </label>
                    <input type="text" name="bairro" placeholder="Bairro" value="{{ old('bairro', $membro->bairro) }}">
                </div>
            </div>
            <div class="form-block">
                <h3>Informações específicas</h3>
                <div class="form-item">
                    <label for="data_batismo">Data de Batismo: </label>
                    <input type="date" name="data_batismo" id="" placeholder="Data de batismo">
                </div>
                <div class="form-item">
                    <label for="denominacao_origem">Denominação de Origem: </label>
                    <input type="text" name="denominacao_origem" id="" placeholder="Denominação de origem">
                </div>
                <div class="form-item">
                    <label for="ministerio">Ministério: </label>
                    <select name="ministerio" id="">
                        @foreach ($ministerios as $item)
                            <option value="{{$item->id}}" {{ old('ministerio', $membro->ministerio_id) == $item->id ? 'selected' : '' }}>
                                {{$item->titulo}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-block">
                <h3>Filiação</h3>
                <div class="form-item">
                    <label for="nome_paterno">Nome paterno: </label>
                    <input type="text" name="nome_paterno" placeholder="Nome paterno">
                </div>
                <div class="form-item">
                    <label for="nome_materno">Nome materno: </label>
                    <input type="text" name="nome_materno" placeholder="Nome materno">
                </div>
            </div>
            <div class="form-options">
                <button class="btn" type="submit"><i class="bi bi-save"></i> Atualizar Membro</button>
                <a href="/"><button type="button" class="btn"><i class="bi bi-x-circle"></i> Cancelar</button></a>
            </div>
        </div><!--form-control-->
    </form>
</div>

@endsection