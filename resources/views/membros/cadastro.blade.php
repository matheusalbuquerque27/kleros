@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')

@if ($errors->any())
    <div class="msg">
        <div class="error">
            <ul>
                {{$errors->first()}}
            </ul>
        </div>
    </div>
@endif

<div class="container">
    <h1>Novo membro</h1>
    <form action="/membros" method="post">
        @csrf
        <div class="form-control">
            <div class="form-block">
                <h3>Informações básicas</h3>
                <div class="form-item">
                    <label for="Nome">Nome: </label>
                    <input type="text" name="nome" id="nome" placeholder="Nome completo" value="{{ old('nome') }}">
                </div>
                <div class="form-item">
                    <label for="rg">RG: </label>
                    <input type="text" name="rg" id="rg" placeholder="RG" value="{{ old('rg') }}">
                </div>
                <div class="form-item">
                    <label for="cpf">CPF: </label>
                    <input type="text" name="cpf" id="cpf" placeholder="CPF" value="{{ old('cpf') }}">
                </div>
                <div class="form-item">
                    <label for="data_nascimento">Data de nascimento: </label>
                    <input type="date" name="data_nascimento" id="data_nascimento" value="{{ old('data_nascimento') }}">
                </div>
                <div class="form-item">
                    <label for="sexo">Sexo: </label>
                    <select name="sexo" id="sexo">
                        <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Feminino</option>
                    </select>
                </div>
                <div class="form-item">
                    <label for="telefone">Telefone: </label>
                    <input type="tel" id="telefone" name="telefone" placeholder="(00)00000-0000" value="{{ old('telefone') }}">
                </div>
                <div class="form-item">
                    <label for="email">Email: </label>
                    <input type="tel" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                </div>
                <div class="form-item">
                    <label for="estado_civil">Estado civil: </label>
                    <select name="estado_civil" id="estado_civil">
                        @foreach ($estado_civil as $item)
                            <option value="{{$item->id}}">{{$item->titulo}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-item">
                    <label for="escolaridade">Escolaridade: </label>
                    <select name="escolaridade" id="escolaridade">
                        @foreach ($escolaridade as $item)
                            <option value="{{$item->id}}">{{$item->titulo}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-item">
                    <label for="profissao">Profissão: </label>
                    <input type="text" name="profissao" id="profissao" placeholder="Profissão" value="{{ old('profissao') }}">
                </div>
            </div>
            <div class="form-block">
                <h3>Informações de endereço</h3>
                <div class="form-item">
                    <label for="endereco">Endereço: </label>
                    <input type="text" name="endereco" id="endereco" placeholder="Endereço" value="{{ old('endereco') }}">
                </div>
                <div class="form-item">
                    <label for="numero">Número: </label>
                    <input type="text" name="numero" id="numero" placeholder="Número" value="{{ old('numero') }}">
                </div>
                <div class="form-item">
                    <label for="complemento">Complemento: </label>
                    <input type="text" name="complemento" id="complemento" placeholder="Complemento" value="{{ old('complemento') }}">
                </div>
                <div class="form-item">
                    <label for="bairro">Bairro: </label>
                    <input type="text" name="bairro" id="bairro" placeholder="Bairro" value="{{ old('bairro') }}">
                </div>
                <div class="form-item">
                    <label for="cep">CEP: </label>
                    <input type="text" name="cep" id="cep" placeholder="00000-000" value="{{ old('bairro') }}">
                </div>
            </div>
            <div class="form-block">
                <h3>Informações específicas</h3>
                <div class="form-item">
                    <label for="data_batismo">Data de Batismo: </label>
                    <input type="date" name="data_batismo" id="data_batismo" placeholder="Data de batismo" value="{{ old('data_batismo') }}">
                </div>
                <div class="form-item">
                    <label for="denominacao_origem">Denominação de Origem: </label>
                    <input type="text" name="denominacao_origem" id="denominacao_origem" placeholder="Denominação de origem" value="{{ old('denominacao_origem') }}">
                </div>
                <div class="form-item">
                    <label for="ministerio">Ministério: </label>
                    <select name="ministerio" id="ministerio">
                        @foreach ($ministerios as $item)
                            <option value="{{$item->id}}">{{$item->titulo}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-item">
                    <label for="data_consagracao">Data de Consagração: </label>
                    <input type="date" name="data_consagracao" id="data_consagracao" placeholder="Data de consagração" value="{{ old('data_consagracao') }}">
                </div>
            </div>
            <div class="form-block">
                <h3>Filiação</h3>
                <div class="form-item">
                    <label for="nome_paterno">Nome paterno: </label>
                    <input type="text" name="nome_paterno" id="nome_paterno" placeholder="Nome paterno" value="{{ old('nome_paterno') }}">
                </div>
                <div class="form-item">
                    <label for="nome_materno">Nome materno: </label>
                    <input type="text" name="nome_materno" id="nome_materno" placeholder="Nome materno" value="{{ old('nome_materno') }}">
                </div>
            </div>
            <div class="form-options">
                <button class="btn" type="submit"><i class="bi bi-plus-circle"></i> Adicionar Membro</button>
                <a href="/membros/painel"><button type="button" class="btn"><i class="bi bi-arrow-return-left"></i> Voltar</button></a>
            </div>
        </div><!--form-control-->
    </form>
</div>

@endsection