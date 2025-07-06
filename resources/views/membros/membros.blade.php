@extends('layouts.main')

@section('title', 'Novo Membro - AD Jerusalém')

@section('content')

@if ($errors->any())
    <div class="msg">
        <div class="error">
            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
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
                    <input type="text" name="nome" placeholder="Nome completo" value="{{ old('nome') }}">
                </div>
                <div class="form-item">
                    <label for="rg">RG: </label>
                    <input type="text" name="rg" placeholder="RG" value="{{ old('rg') }}">
                </div>
                <div class="form-item">
                    <label for="cpf">CPF: </label>
                    <input type="text" name="cpf" placeholder="CPF" value="{{ old('cpf') }}">
                </div>
                <div class="form-item">
                    <label for="data_nascimento">Data de nascimento: </label>
                    <input type="date" name="data_nascimento" value="{{ old('data_nascimento') }}">
                </div>
                <div class="form-item">
                    <label for="telefone">Telefone: </label>
                    <input type="tel" id="telefone" name="telefone" placeholder="(00)00000-0000" value="{{ old('telefone') }}">
                </div>
                <div class="form-item">
                    <label for="estado_civil">Estado civil: </label>
                    <select name="estado_civil" id="" value="{{ old('estado_civil') }}">
                        @foreach ($estado_civil as $item)
                            <option value="{{$item->id}}">{{$item->titulo}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-item">
                    <label for="escolaridade">Escolaridade: </label>
                    <select name="escolaridade" id="" value="{{ old('escolaridade') }}">
                        @foreach ($escolaridade as $item)
                            <option value="{{$item->id}}">{{$item->titulo}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-item">
                    <label for="profissao">Profissão: </label>
                    <input type="text" name="profissao" placeholder="Profissão" value="{{ old('profissao') }}">
                </div>
            </div>
            <div class="form-block">
                <h3>Informações de endereço</h3>
                <div class="form-item">
                    <label for="endereco">Endereço: </label>
                    <input type="text" name="endereco" placeholder="Endereço" value="{{ old('endereco') }}">
                </div>
                <div class="form-item">
                    <label for="numero">Número: </label>
                    <input type="text" name="numero" placeholder="Número" value="{{ old('numero') }}">
                </div>
                <div class="form-item">
                    <label for="bairro">Bairro: </label>
                    <input type="text" name="bairro" placeholder="Bairro" value="{{ old('bairro') }}">
                </div>
            </div>
            <div class="form-block">
                <h3>Informações específicas</h3>
                <div class="form-item">
                    <label for="data_batismo">Data de Batismo: </label>
                    <input type="date" name="data_batismo" id="" placeholder="Data de batismo" value="{{ old('data_batismo') }}">
                </div>
                <div class="form-item">
                    <label for="denominacao_origem">Denominação de Origem: </label>
                    <input type="text" name="denominacao_origem" id="" placeholder="Denominação de origem" value="{{ old('denominacao_origem') }}">
                </div>
                <div class="form-item">
                    <label for="ministerio">Ministério: </label>
                    <select name="ministerio" id="" value="{{ old('ministerio') }}">
                        @foreach ($ministerios as $item)
                            <option value="{{$item->id}}">{{$item->titulo}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-item">
                    <label for="data_consagracao">Data de Consagração: </label>
                    <input type="date" name="data_consagracao" id="" placeholder="Data de consagração" value="{{ old('data_consagracao') }}">
                </div>
            </div>
            <div class="form-block">
                <h3>Filiação</h3>
                <div class="form-item">
                    <label for="nome_paterno">Nome paterno: </label>
                    <input type="text" name="nome_paterno" placeholder="Nome paterno" value="{{ old('nome_paterno') }}">
                </div>
                <div class="form-item">
                    <label for="nome_materno">Nome materno: </label>
                    <input type="text" name="nome_materno" placeholder="Nome materno" value="{{ old('nome_materno') }}">
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