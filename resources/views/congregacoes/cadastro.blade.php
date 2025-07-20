@extends('layouts.main')

@section('title', 'Check-In Institucional')

@section('content')
@if($errors->any())
<div class="msg">
    <div class="error">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<div class="container">

    <h1>Check-In Institucional</h1>
    <form action="" method="POST">
        @csrf
        <div class="center">
            <p>Preencha as informações abaixo para registrar sua congregação.</p>
        </div>
        <div class="form-control">
            <div class="form-block">
                <h3>Selecione sua igreja:</h3>
                <div class="form-item">
                    <label for="igreja">Igrejas já cadastradas:</label>
                    <select name="igreja" id="igreja" required>
                        <option value="">Selecione uma igreja</option>
                        @foreach($denominacoes as $denominacao)
                            <option value="{{ $denominacao->id }}">{{ $denominacao->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <p>Não encontrou sua igreja? <a href="{{ route('denominacoes.create') }}">Clique aqui</a> para cadastrá-la.</p>
            </div>
            <div class="form-block">
                <h3>Informações básicas</h3>
                <div class="form-item">
                    <label for="nome">Identificação:</label>
                    <input type="text" id="nome" name="nome" placeholder="Como é identificada sua congregação?" required>
                </div>
                <div class="form-item">
                    <label for="cnpj">CNPJ:</label>
                    <input type="text" id="cnpj" name="cnpj" placeholder="CNPJ da congregação" required>
                </div>
                <div class="form-item">
                    <label for="telefone">Telefone:</label>
                    <input type="tel" id="telefone" name="telefone" required>
                </div>
                <div class="form-item">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" placeholder="Email institucional" required>
                </div>
                <div class="form-item">
                    <label for="site">Site:</label>
                    <input type="url" id="site" name="site" placeholder="https://www.seusite.com.br">
                </div>
            </div>
            <div class="form-block">
                <h3>Localização</h3>
                <div class="form-item">
                    <label for="endereco">Endereço:</label>
                    <input type="text" id="endereco" name="endereco" placeholder="Rua/Avenida/Logradouro">
                </div>
                <div class="form-item">
                    <label for="numero">Número:</label>
                    <input type="text" id="numero" name="numero" placeholder="Número do endereço">
                </div>
                <div class="form-item">
                    <label for="bairro">Bairro:</label>
                    <input type="text" id="bairro" name="bairro" placeholder="Bairro">
                </div>
                <div class="form-item">
                    <label for="complemento">Complemento:</label>
                    <input type="text" id="complemento" name="complemento" placeholder="Complemento">
                </div>
                <div class="form-item">
                    <label for="cep">CEP:</label>
                    <input type="text" id="cep" name="cep" placeholder="CEP">
                </div>
                <div class="form-item">
                    <label for="cidade">Cidade:</label>
                    <select name="cidade" id="cidade">
                        <option value="">Selecione a cidade</option>
                        <option value="cidade1">Cidade 1</option>
                        <option value="cidade2">Cidade 2</option>
                        <option value="cidade3">Cidade 3</option>
                    </select>
                </div>
                <div class="form-item">
                    <label for="estado">Estado:</label>
                    <select name="estado" id="estado">
                        <option value="">Selecione o estado</option>
                        <option value="estado1">Estado 1</option>
                        <option value="estado2">Estado 2</option>
                        <option value="estado3">Estado 3</option>
                    </select>
                </div>
                <div class="form-item">
                    <label for="pais">País:</label>
                    <select name="pais" id="pais">
                        <option value="">Selecione o país</option>
                        <option value="1">Brasil</option>
                    </select>
                </div>
            </div>
            
            <div class="form-options">
                <button class="btn" type="submit">Salvar</button>
                <a href="{{ route('index') }}"><button class="btn" type="button">Voltar</button></a>
            </div>
        </div>{{-- Form-control --}}
        
    </form>


</div>

@endsection