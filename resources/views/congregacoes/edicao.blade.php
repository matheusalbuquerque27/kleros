@extends('layouts.main')

@section('title', 'Configurações')

@section('content')

<div class="container">
    <h1>Configurações</h1>
    <form action="/configuracoes/{{$congregacao->id}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- Método PUT para atualização --}}
        <div class="info">
            <h3>Perfil Institucional</h3>
            <div class="form-control">
                <div class="form-item">
                    <label for="identificacao">Identificação</label>
                    <input type="text" name="identificacao" id="identificacao" value="{{$congregacao->identificacao}}">
                </div>
                <div class="form-item">
                    <label for="cnpj">CNPJ</label>
                    <input type="text" name="cnpj" id="cnpj" value="{{$congregacao->cnpj}}">
                </div>
                <div class="form-item">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="{{$congregacao->email}}" autocomplete="email">
                </div>
                <div class="form-item">
                    <label for="telefone">Telefone</label>
                    <input type="tel" name="telefone" id="telefone" value="{{$congregacao->telefone}}">
                </div>
            </div>
            <h3>Características Visuais</h3>
            <div class="form-control">
                <h4>Arquivos e imagens</h4>
                <div class="form-item">
                    <label for="logo">Logo da congregação</label>
                    <img class="image-small" src="{{asset('storage/'.$congregacao->config->logo_caminho)}}" alt="">
                    <input type="file" name="logo" id="logo">
                </div>
                <div class="form-item">
                    <label for="banner">Banner de login</label>
                    <img class="image-small" src="{{asset('storage/'.$congregacao->config->banner_caminho)}}" alt="">
                    <input type="file" name="banner" id="banner">
                </div>
            </div>
            <h3>Cores e estilo</h3>
            <div class="form-control">
                <div class="form-item">
                    <label for="cor_primaria">Cor Primária</label>
                    <input type="color" name="conjunto_cores[primaria]" id="cor_primaria" value="{{$congregacao->config->conjunto_cores['primaria']}}">
                </div>
                <div class="form-item">
                    <label for="cor_secundaria">Cor Primária</label>
                    <input type="color" name="conjunto_cores[secundaria]" id="cor_secundaria" value="{{$congregacao->config->conjunto_cores['secundaria']}}">
                </div>
                <div class="form-item">
                    <label for="cor_terciaria">Cor Primária</label>
                    <input type="color" name="conjunto_cores[terciaria]" id="cor_terciaria" value="{{$congregacao->config->conjunto_cores['terciaria']}}">
                </div>
                
                <div class="form-item">
                    <label for="fonte">Fonte de texto</label>
                    <select name="fonte" id="fonte">
                        <option value="Teko" @selected($congregacao->config->font_family === 'Teko')>Teko</option>
                        <option value="Roboto" @selected($congregacao->config->font_family === 'Roboto')>Roboto</option>
                        <option value="Open Sans" @selected($congregacao->config->font_family === 'Open Sans')>Open Sans</option>
                        <option value="Oswald" @selected($congregacao->config->font_family === 'Oswald')>Oswald</option>
                        <option value="Saira" @selected($congregacao->config->font_family === 'Saira')>Saira</option>
                    </select>
                </div>
                <div class="form-item">
                    <h4 class="w100 right"><div class="tag">Exemplo fonte escolhida:</div><span class="right" id="font-preview"> Tudo posso naquele que me fortalece.</span></h4>
                </div>
                <div class="form-item">
                    <label>Tema visual</label>
                    <div class="form-square" id="tema">
                        <div>
                            <input type="radio" id="classico" name="tema" value="1" @checked($congregacao->config->tema->id == 1)>
                            <label for="classico">Clássico</label>
                        </div>
                        <div>
                            <input type="radio" id="moderno" name="tema" value="2" @checked($congregacao->config->tema->id == 2)>
                            <label for="moderno">Moderno</label>
                        </div>
                        <div>
                            <input type="radio" id="vintage" name="tema" value="3" @checked($congregacao->config->tema->id == 3)>
                            <label for="vintage">Vintage</label>
                        </div>
                    </div>
                </div>
            </div>
            <h3>Localização</h3>
            <div class="form-control">
                <div class="form-item">
                    <label for="endereco">Endereço</label>
                    <input type="text" name="endereco" value="{{$congregacao->endereco}}">
                </div>
                <div class="form-item">
                    <label for="numero">Número</label>
                    <input type="text" name="numero" value="{{$congregacao->numero}}">
                </div>
                <div class="form-item">
                    <label for="complemento">Complemento</label>
                    <input type="text" name="complemento" value="{{$congregacao->complemento}}">
                </div>
                <div class="form-item">
                    <label for="bairro">Bairro</label>
                    <input type="text" name="bairro" value="{{$congregacao->bairro}}">
                </div>
                <div class="form-item">
                    <label for="cidade">Cidade</label>
                    <select name="cidade" id="cidade">
                        <option value="">Selecione uma cidade</option>
                        @foreach($cidades as $item)
                            <option value="{{$item->id}}" @selected($congregacao->cidade_id == $item->id)>{{$item->nome}}</option>   
                        @endforeach
                    </select>
                </div>
                <div class="form-item">
                    <label for="bairro">Estado</label>
                    <select name="estado" id="estado">
                        <option value="">Selecione um estado/região</option>
                        @foreach($estados as $item)
                            <option value="{{$item->id}}" @selected($congregacao->estado_id == $item->id)>{{$item->nome}}</option>   
                        @endforeach
                    </select>
                </div>
                <div class="form-item">
                    <label for="bairro">Bairro</label>
                    <select name="pais" id="pais">
                        <option value="">Selecione um país</option>
                        @foreach($paises as $item)
                            <option value="{{$item->id}}" @selected($congregacao->pais_id == $item->id)>{{$item->nome}}</option>   
                        @endforeach
                    </select>
                </div>
                <div class="form-options">
                    <button class="btn" type="submit"><i class="bi bi-save"></i> Atualizar</button>
                    <button class="btn" type="button"><i class="bi bi-skip-backward"></i> Restaurar</button>
                    <a href="/"><button type="button" class="btn"><i class="bi bi-arrow-return-left"></i> Voltar</button></a>
                </div>
            </div>
        </div>{{--info--}}
    </form>
</div>
    
@endsection

@push('scripts')

<script>
    $('#fonte').on('change', function() {
        $('#font-preview').css('font-family', this.value);
    });
</script>

@endpush