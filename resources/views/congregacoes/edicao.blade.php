@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

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
                    <img class="image-small" id="logo-img" src="{{asset('storage/'.$congregacao->config->logo_caminho)}}" alt="">
                    <div class="logo">
                        <span id="file-logo">Nenhum arquivo selecionado</span>
                        <label for="logo" class="btn-line"><i class="bi bi-upload"></i> Upload</label>
                        <input type="file" name="logo" id="logo" url="">
                        <input type="hidden" name="logo_acervo" id="logo_acervo">
                        <span class="btn-line window_files"><i class="bi bi-hdd"></i> Drive</span>
                    </div>
                </div>
                <div class="form-item">
                    <label for="banner">Banner de login</label>
                    <img class="image-small" id="banner-img" src="{{asset('storage/'.$congregacao->config->banner_caminho)}}" alt="">
                    <div class="banner">
                        <span id="file-banner">Nenhum arquivo selecionado</span>
                        <label for="banner" class="btn-line"><i class="bi bi-upload"></i> Upload</label>
                        <input type="file" name="banner" id="banner" url="">
                        <input type="hidden" name="banner_acervo" id="banner_acervo">
                        <span class="btn-line window_files"><i class="bi bi-hdd"></i> Drive</span>
                    </div>
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
                    <label for="cor_terciaria">Cor Terciária</label>
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
                    <label for="pais">País</label>
                    <select name="pais" id="pais">
                        <option value="">Selecione um país</option>
                        @foreach($paises as $item)
                            <option value="{{$item->id}}" @selected($congregacao->pais_id == $item->id)>{{$item->nome}}</option>   
                        @endforeach
                    </select>
                </div>
                <div class="form-item">
                    <label for="bairro">Estado</label>
                    <select name="estado" id="estado">
                        <option value="">Selecione um estado/região</option>
                    </select>
                </div>
                <div class="form-item">
                    <label for="cidade">Cidade</label>
                    <select name="cidade" id="cidade">
                        <option value="">Selecione uma cidade</option>
                    </select>
                </div>
                <div class="form-options">
                    <button class="btn" type="submit"><i class="bi bi-arrow-clockwise"></i> Atualizar</button>
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
    $(document).ready(function () {
        initFileSelector();
    });

    $('#fonte').on('change', function() {
        $('#font-preview').css('font-family', this.value);
    });

    $('#logo').on('change', function() {
        const fileName = this.files[0] ? this.files[0].name : 'Nenhum arquivo selecionado';
        $('#file-logo').text(fileName);
    });

    $('#banner').on('change', function() {
        const fileName = this.files[0] ? this.files[0].name : 'Nenhum arquivo selecionado';
        $('#file-banner').text(fileName);
    });
</script>

<script>
    function initFileSelector() {
    let currentRoute = null;

    // Abrir modal e guardar o ID do input
    $(document).on('click', '.window_files', function () {
        currentRoute = $(this).closest('div').attr('class');
        abrirJanelaModal('{{ route("arquivos.imagens") }}');
    });

    // Selecionar card
    $(document).on('click', '.card-arquivo', function () {
        $('.card-arquivo').removeClass('selected');
        $(this).addClass('selected');

        $('#selected').toggleClass('inactive', $('.card-arquivo.selected').length === 0);
    });

    // Clique fora para desmarcar
    $(document).on('click', function (event) {
        if (!$(event.target).closest('.card-arquivo, .window_files, #selected').length) {
            $('.card-arquivo').removeClass('selected');
            $('#selected').addClass('inactive');
        }
    });

    // Confirmar seleção
    $(document).on('click', '#selected', function () {
        const selectedFile = $('.card-arquivo.selected');
        if (selectedFile.length > 0) {
            const title = selectedFile.find('.titulo').text().trim();
            const imgSrc = selectedFile.find('img').attr('src');

            $('#' + currentRoute + '-img').attr('src', imgSrc);
            $('.' + currentRoute + ' #file-name').text(title || imgSrc);
            $('#' + currentRoute + '_acervo').val(imgSrc) // Atualiza o atributo URL do input

            fecharJanelaModal();
            currentRoute = null; // Limpa para evitar reuso incorreto
        }
    });
}
</script>

<!--Script para manipulação de imagens quando chamar form_imagens-->
<script>

    $(document).on('change', '#upload-img', function() {
    
    // Pega o formulário mais próximo do input de arquivo
    const form = $(this).closest('form')[0];
    const formData = new FormData(form);

    $.ajax({
        url: $(form).attr('action'), // Pega a URL do atributo action do formulário
        type: 'POST',
        data: formData,
        processData: false, // IMPEDE que o jQuery processe os dados (essencial para arquivos)
        contentType: false, // IMPEDE que o jQuery defina o cabeçalho (essencial para arquivos)
        success: function(response) {
            console.log(response);
            flashMsg('Upload realizado com sucesso!');
            $('.acervo').load('/arquivos/lista_imagens');
        },
        error: function(xhr, status, error) {
            // Aqui você lida com erros, como arquivos muito grandes ou falhas na requisição
            console.error('Erro no upload:', xhr.responseText);
            alert('Erro ao realizar o upload.');
        }
        });
    });

    // Seleção do card
    $(document).on('click', '.card-arquivo', function (e) {
    // se o clique foi no botão de exclusão, não selecione
    if ($(e.target).closest('.delete-img').length) return;

    $('.card-arquivo').removeClass('selected');
    $(this).addClass('selected');
    $('#selected').toggleClass('inactive', $('.card-arquivo.selected').length === 0);
    });

    // Exclusão (apenas quando clicar no botão)
    $(document).on('click', '.delete-img', async function (e) {
    e.preventDefault();
    e.stopPropagation();           // impede outros handlers no mesmo elemento/document

    const $btn = $(this);
    const card = $btn.closest('.card-arquivo');
    const arquivoId = $btn.attr('id');

    if (!arquivoId) {
        console.error('ID do arquivo não encontrado.');
        alert('Erro: ID do arquivo não encontrado.');
        return;
    }

    const respostaConfirmacao = await confirmarAcao("Tem certeza que deseja excluir este arquivo?");
    if (!respostaConfirmacao) return;

    const deleteUrl = `/arquivos/${arquivoId}`;
    $.ajax({
        url: deleteUrl,
        type: 'DELETE',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (response) {
        card.remove();
        flashMsg(response.success || 'Arquivo excluído.')
        },
        error: function (xhr) {
        alert('Erro ao excluir o arquivo.');
        console.error(xhr.responseText);
        }
    });
    });

</script>

<!--Script para seleção dinâmica de estado e cidade-->
<script>
document.addEventListener("DOMContentLoaded", function () {
    let paisSelect   = document.getElementById('pais');
    let estadoSelect = document.getElementById('estado');
    let cidadeSelect = document.getElementById('cidade');

    // valores já salvos
    let selectedPais   = "{{ $congregacao->pais_id ?? '' }}";
    let selectedEstado = "{{ $congregacao->estado_id ?? '' }}";
    let selectedCidade = "{{ $congregacao->cidade_id ?? '' }}";

    // carrega estados quando muda o país
    paisSelect.addEventListener('change', function () {
        carregarEstados(this.value, null);
    });

    // carrega cidades quando muda o estado
    estadoSelect.addEventListener('change', function () {
        carregarCidades(this.value, null);
    });

    // 🔹 Se já existe país salvo, carregar estados e cidades automaticamente
    if (selectedPais) {
        carregarEstados(selectedPais, selectedEstado, () => {
            if (selectedEstado) {
                carregarCidades(selectedEstado, selectedCidade);
            }
        });
    }

    // funções auxiliares
    function carregarEstados(paisId, estadoId = null, callback = null) {
        estadoSelect.innerHTML = '<option value="">Carregando...</option>';
        cidadeSelect.innerHTML = '<option value="">Selecione uma cidade</option>';

        if (!paisId) {
            estadoSelect.innerHTML = '<option value="">Selecione um estado</option>';
            return;
        }

        fetch(`/estados/${paisId}`)
            .then(res => res.json())
            .then(estados => {
                estadoSelect.innerHTML = '<option value="">Selecione um estado</option>';
                estados.forEach(estado => {
                    let selected = (estadoId && estado.id == estadoId) ? 'selected' : '';
                    estadoSelect.innerHTML += `<option value="${estado.id}" ${selected}>${estado.nome}</option>`;
                });
                if (callback) callback();
            });
    }

    function carregarCidades(estadoId, cidadeId = null) {
        cidadeSelect.innerHTML = '<option value="">Carregando...</option>';

        if (!estadoId) {
            cidadeSelect.innerHTML = '<option value="">Selecione uma cidade</option>';
            return;
        }

        fetch(`/cidades/${estadoId}`)
            .then(res => res.json())
            .then(cidades => {
                cidadeSelect.innerHTML = '<option value="">Selecione uma cidade</option>';
                cidades.forEach(cidade => {
                    let selected = (cidadeId && cidade.id == cidadeId) ? 'selected' : '';
                    cidadeSelect.innerHTML += `<option value="${cidade.id}" ${selected}>${cidade.nome}</option>`;
                });
            });
    }
});
</script>

@endpush