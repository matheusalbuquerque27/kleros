@extends('layouts.main')

@section('title', 'Configurações')

@section('content')

<div class="container">
    <h1>Configurações</h1>
    <form action="/configuracoes" method="post">
        @csrf
        <div class="info">
            <h3>Perfil Institucional</h3>
            <div class="form-control">
                <div class="form-item">
                    <label for="identificacao">Identificação</label>
                    <input type="text" value="Ilha Solteira">
                </div>
                <div class="form-item">
                    <label for="cnpj">CNPJ</label>
                    <input type="text" value="1000002002235">
                </div>
                <div class="form-item">
                    <label for="email">Email</label>
                    <input type="email" value="adjisa@gmail.com">
                </div>
                <div class="form-item">
                    <label for="telefone">Telefone</label>
                    <input type="tel" value="1000002002235">
                </div>
            </div>
            <h3>Características Visuais</h3>
            <div class="form-control">
                <h4>Arquivos e imagens</h4>
                <div class="form-item">
                    <label for="logo">Logo da congregação</label>
                    <img class="image-small" src="{{asset($congregacao->config->logo_caminho)}}" alt="">
                    <input type="file" name="logo" id="logo">
                </div>
                <div class="form-item">
                    <label for="banner">Banner de login</label>
                    <img class="image-small" src="{{asset($congregacao->config->banner_caminho)}}" alt="">
                    <input type="file" name="banner" id="banner">
                </div>
            </div>
            <h3>Cores e estilo</h3>
            <div class="form-control">
                <div class="form-item">
                    <label for="cor_primaria">Cor Primária</label>
                    <input type="color" name="cor_primaria" id="cor_primaria" value="{{$congregacao->config->conjunto_cores['primaria']}}">
                </div>
                <div class="form-item">
                    <label for="cor_secundaria">Cor Primária</label>
                    <input type="color" name="cor_secundaria" id="cor_secundaria" value="{{$congregacao->config->conjunto_cores['secundaria']}}">
                </div>
                <div class="form-item">
                    <label for="cor_terciaria">Cor Primária</label>
                    <input type="color" name="cor_terciaria" id="cor_terciaria" value="{{$congregacao->config->conjunto_cores['terciaria']}}">
                </div>
                <div class="form-item">
                    <p>[Exemplo fonte escolhida]: Tudo posso naquele que me fortalece.</p>
                </div>
                <div class="form-item">
                    <label for="fonte">Fonte de texto</label>
                    <select name="fonte" id="fonte">
                        <option value="Teko">Teko</option>
                        <option value="Roboto">Roboto</option>
                    </select>
                </div>
                <div class="form-item">
                    <p>[Exemplo tema escolhido]:</p>
                    <button class="">Teste Clássico</button>
                </div>
                <div class="form-item">
                    <label for="tema">Tema visual</label>
                    <div class="form-square">
                        <div>
                            <input type="radio" id="automatica" name="requer_inscricao" value="0" checked>
                            <label for="automatica">Clássico</label>
                        </div>
                        <div>
                            <input type="radio" id="manual" name="requer_inscricao" value="1">
                            <label for="manual">Moderno</label>
                        </div>
                        <div>
                            <input type="radio" id="manual" name="requer_inscricao" value="1">
                            <label for="manual">Vibrante</label>
                        </div>
                    </div>
                </div>
            </div>
            <h3>Localização</h3>
            <div class="form-control">
                <div class="form-item">
                    <label for="endereco">Endereço</label>
                    <input type="text" value="1000002002235">
                </div>
                <div class="form-item">
                    <label for="numero">Número</label>
                    <input type="text" value="2444">
                </div>
                <div class="form-item">
                    <label for="complemento">Complemento</label>
                    <input type="text" value="Quadra X, 79">
                </div>
                <div class="form-item">
                    <label for="bairro">Bairro</label>
                    <input type="text" value="Santa Tereza">
                </div>
                <div class="form-item">
                    <label for="cidade">Cidade</label>
                    <select name="cidade" id="cidade">
                        <option value="São Paulo">São Paulo</option>
                    </select>
                </div>
                <div class="form-item">
                    <label for="bairro">Estado</label>
                    <select name="estado" id="estado">
                        <option value="São Paulo">São Paulo</option>
                    </select>
                </div>
                <div class="form-item">
                    <label for="bairro">Bairro</label>
                    <select name="pais" id="pais">
                        <option value="Brasil">Brasil</option>
                    </select>
                </div>
                <div class="form-options">
                    <button class="btn" type="submit"><i class="bi bi-save"></i> Salvar atualizações</button>
                    <button class="btn" type="submit"><i class="bi bi-skip-backward"></i> Restaurar</button>
                    <a href="/"><button type="button" class="btn"><i class="bi bi-arrow-return-left"></i> Voltar</button></a>
                </div>
            </div>
        </div>{{--info--}}
    </form>
</div>
    
@endsection

@push('script')

<script>
    
</script>

@endpush