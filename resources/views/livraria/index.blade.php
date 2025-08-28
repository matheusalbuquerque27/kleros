@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')

<div class="container">
    <h1>Livraria</h1>
    <div class="info">
        <h3>Recomendados</h3>
        <form action="" method="POST">
            @csrf
            <div class="search-panel">
                <div class="search-panel-item">
                    <label><i class="bi bi-funnel"></i></label>
                    <select name="" id="filtro">
                        <option value="titulo">Título</option>
                        <option value="autor">Autor</option>
                    </select>
                </div>
                <div class="search-panel-item">
                    <label><i class="bi bi-search"></i></label>
                    <input type="text" name="chave" placeholder="Pesquisar" id="chave">
                </div>
                 <div>
                    <button id="btn_filtrar"><i class="bi bi-search"></i> Procurar</button>
                </div>
            </div>
        </form>
        <section class="vitrine-container">
            <!-- GIF de carregamento -->
            <div id="carregando" style="display: none; position: fixed; top:50vh; left:50vw;z-index:999;">
                <img src="{{ asset('storage/images/loading.gif') }}" alt="Carregando..." width="80">
                <p>Buscando livros...</p>
            </div>
            <div class="vitrine">
                @foreach ($livros as $item)
                    <div class="card-livro">
                    <img src="{{$item['imagem']}}" alt="{{$item['titulo']}}">
                    <div class="conteudo-livro">
                        <div>
                        <h3 class="titulo-livro">{{$item['titulo']}}</h3>
                        <p class="autor-livro">{{$item['autor']}}</p>
                        </div>
                        <div class="botao-comprar">
                        <a href="{{$item['description']}}" target="_blank"><i class="bi bi-search"></i> Ver mais</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @include('noticias.includes.destaques')
    </div>
</div>

@endsection

@push('scripts')

<script>
    $(document).ready(function(){
        $('#btn_filtrar').click(function(event){

            event.preventDefault();
            
            const _token = $('meta[name="csrf-token"]').attr('content');
            let filtro = $('#filtro').val();
            let chave = $('#chave').val();

            $('#carregando').show();

            $.post('/livraria/search', { _token, filtro, chave }, function(response){
                
                var view = response.view                

                $('.vitrine').html(view);
                $('#carregando').hide();

            }).fail(function(err) {
                console.error('Erro ao buscar livros:', err);
                $('.msg').show('Ops, ocorreu um erro de requisição, tente novamente.');
                $('#carregando').hide();
            });
            
        })
    })
</script>
    
@endpush