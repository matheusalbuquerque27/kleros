<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>
        <link rel="shortcut icon" href="{{asset('storage/'.$congregacao->config->logo_caminho)}}" type="image/x-icon">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Teko" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Oswald" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Saira" rel="stylesheet">
        
        <!-- Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        
        <!-- CSS -->
        @vite(['resources/css/app.scss', 'resources/js/app.js'])

        <!-- Swipper para interações -->
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

        <style>
        /* CSS dinâmico injetado aqui */
        :root {
            
            --primary-color: {{$congregacao->config->conjunto_cores['primaria'] ?? '#677b96'}};
            --secondary-color: {{$congregacao->config->conjunto_cores['secundaria'] ?? '#0a1929'}};
            --terciary-color: {{$congregacao->config->conjunto_cores['terciaria'] ?? '#f44916'}};
            --primary-contrast: {{ getContrastTextColor($congregacao->config->conjunto_cores['primaria'])}};
            --secondary-contrast: {{ getContrastTextColor($congregacao->config->conjunto_cores['secundaria'])}};
            --terciary-contrast: {{ getContrastTextColor($congregacao->config->conjunto_cores['terciaria'])}};
            --text-font: {{$congregacao->config->font_family}};

            --background-color: {{$congregacao->config->tema->propriedades['cor-fundo']}};
            --text-color: {{$congregacao->config->tema->propriedades['cor-texto']}};
            --border-style: {{$congregacao->config->tema->propriedades['borda']}}
        }
        </style>
    </head>
    <body>
        <div class="layout-wrapper">
            <div class="popup">
                <h2>Aviso</h2>
                <p id="msg_content"></p>
                <button id="confirmaBtn"><i class="bi bi-check"></i> Confirmar</button>
                <button id="cancelaBtn"><i class="bi bi-x"></i> Cancelar</button> 
            </div>
            <header class="nao-imprimir">
                <nav class="main-navbar">
                    <div class="nav-logo">
                        <img src="{{asset('storage/'.$congregacao->config->logo_caminho)}}" alt="{{$congregacao->denominacao->nome}} Logo">
                    </div>
                    <div class="nav-menu">
                        <ul>
                            <a href="/"><li><i class="bi bi-kanban"></i> Controle</li></a>
                            <a href="/membros/painel"><li><i class="bi bi-people"></i> Membros</li></a>
                            <a href="/cadastros"><li><i class="bi bi-journals"></i> Cadastros</li></a>
                            <a href="/visitantes/adicionar"><li><i class="bi bi-people-fill"></i> Visitantes</li></a>
                        </ul>
                    </div>
                    <div class="login_info">
                        <a href="/logout" title="Sair"><p><i class="bi bi-box-arrow-right"></i></p></a>
                        <a href="/" title="Notificações"><p><i class="bi bi-bell-fill"></i></p></a>
                        <img class="avatar" src="{{ asset('storage/'.optional(auth()->user()->membro)->foto) }}" title="{{optional(auth()->user()->membro)->nome}}" alt="">
                    </div>
                </nav>
            </header>
            <main class="content">
                @if (session('msg'))
                    <div class="msg">
                        <div class="success"><div class="close"><i class="bi bi-x"></i></div> {{ session('msg') }}</div>
                    </div>
                @endif
                @if (session('msg-error'))
                    <div class="msg">
                        <div class="error"> {{ session('msg-error') }}</div>
                    </div>
                @endif
                <nav class="left-navbar nao-imprimir">
                    <div class="menu-btn">
                        <a href="{{route('tutoriais.index')}}"><span title="Tutoriais" id="btn-tutorial"><i class="bi bi-question-octagon"></i></span></a>
                        <a href="{{route('configuracoes.atualizar', $congregacao->id)}}"><span title="Configurações" id="btn-config"><i class="bi bi-gear"></i></span></a>
                        <span title="Menu Principal" id="btn-menu"><i class="bi bi-list"></i></span>
                    </div>
                    <ul class="menu-content">
                        <a href="{{route('index')}}"><li><span title="Controle"><i class="bi bi-kanban"></i></span><span>Controle Geral</span></li></a>
                        <a href="{{route('membros.painel')}}"><li><span title="Membros"><i class="bi bi-people"></i></span>Membros</span></li></a>
                        <a href="{{route('eventos.agenda')}}"><li><span title="Eventos"><i class="bi bi-calendar-event"></i></span><span>Eventos</span></li></a>
                        <a href="{{route('cultos.agenda')}}"><li><span title="Cultos"><i class="bi bi-bell"></i></span><span>Cultos</span></li></a>
                        <a href="{{route('visitantes.historico')}}"><li><span title="Visitantes"><i class="bi bi-people-fill"></i></span><span>Visitantes</span></li></a>
                        <a href="{{route('departamentos.painel')}}"><li><span title="Departamentos"><i class="bi bi-intersect"></i></span><span>Departamentos</span></li></a>
                        <a href="{{route('celulas.painel')}}"><li><span title="GCA - Células"><i class="bi bi-cup-hot"></i></span><span>GCA - Células</span></li></a>
                        <a href=""><li><span title="Financeiro"><i class="bi bi-currency-exchange"></i></span><span>Financeiro</span></li></a>
                        <a href="{{route('noticias.painel')}}"><li><span title="Notícias"><i class="bi bi-newspaper"></i></span><span>Notícias</span></li></a>
                        <a href="{{route('podcasts.painel')}}"><li><span title="Podcasts"><i class="bi bi-mic-fill"></i></span><span>Podcasts</span></li></a>
                        <a href="{{route('recados.historico')}}"><li><span title="Recados"><i class="bi bi-chat-left-dots"></i></span><span>Recados</span></li></a>
                        <a href="{{route('tutoriais.index')}}"><li><span title="Tutoriais"><i class="bi bi-question-octagon"></i></span><span>Tutoriais</span></li></a>
                        <a href=""><li><span title="Extensões"><i class="bi bi-nut"></i></span><span>Extensões</span></li></a>
                        <a href="{{route('configuracoes.atualizar', $congregacao->id)}}"><li><span title="Configurações"><i class="bi bi-gear"></i></span><span>Configurações</span></li></a>
                    </ul>
                </nav>
                @yield('content')

                
            </main>
            <footer>
                <p>Sistema de Gestão Interna | {{$congregacao->denominacao->nome}} - {{optional($congregacao->cidade)->nome}}/{{optional($congregacao->estado)->uf}}</p>
                @if($congregacao->cnpj) <h4>CNPJ {{$congregacao->cnpj}}</h4> @endif
            </footer>
        </div>
        <!-- Modal Flutuante Reutilizável -->
        <div id="janelaModal" class="modal-overlay" style="display: none;">
            <div class="modal-box">
                <div class="scroll-container">
                    <button id="fecharModal" class="fechar-btn">X</button>
                    <div id="conteudoModal">
                        <!-- Aqui entra o conteúdo dos includes -->
                        @yield('modal-content')
                    </div>
                </div>
            </div>
        </div>

        <!--Aqui vão os scripts que serão usados-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

        <!-- Exemplo de input : <input type="tel" id="telefone" placeholder="(00) 00000-0000"> -->

        <!--Scripts do swipper para interações-->
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

        <script>

             console.log('Swiper:', typeof Swiper !== 'undefined' ? 'Loaded' : 'Not loaded');
            $(document).ready(function(){
            
                $('#telefone').mask('(00) 00000-0000');
                $('#cep').mask('00000-000');
                 $('#cnpj').mask('00.000.000/0000-00');
                
                $('.msg .close').click(function(){
                    this.closest('.msg').remove();
                })

                //Variável de controle do menu
                let menuToggle = false;

                $('#btn-menu').click(function(){
                    if(menuToggle){
                        $('.menu-content').hide();
                        $('.left-navbar').animate({
                            width: '40px'
                        },200)
                    } else {
                        $('.menu-content').show();
                        $('.left-navbar').animate({
                            width: '300px'
                        },200)
                    }
                    //Alterar o valor do Toggle
                    menuToggle = !menuToggle;

                })
            });

            //Função para controle da janela flutuante
            function abrirJanelaModal(url) {
                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('conteudoModal').innerHTML = html;
                        document.getElementById('janelaModal').style.display = 'flex';
                    });
            }

            document.getElementById('fecharModal').addEventListener('click', () => {
                document.getElementById('janelaModal').style.display = 'none';
            });        

        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const destaquesLength = document.querySelectorAll('.swiper-slide').length;

                console.log(destaquesLength)

                const swiper = new Swiper(".mySwiper", {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    loop: destaquesLength > 4, // ativa loop só se houver mais de 4 slides
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    breakpoints: {
                        640: { slidesPerView: 2 },
                        768: { slidesPerView: 3 },
                        1024: { slidesPerView: 4 },
                    },
                });
            });
        </script>

        @stack('scripts')
    </body>
</html>
