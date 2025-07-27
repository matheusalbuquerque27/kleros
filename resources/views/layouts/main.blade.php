<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>
        <link rel="shortcut icon" href="images/logo-ico.ico" type="image/x-icon">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Teko" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital" rel="stylesheet">
        
        <!-- Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- CSS -->
        @vite(['resources/css/app.scss', 'resources/js/app.js'])

        <style>
        /* CSS dinâmico injetado aqui */
        :root {
            <?php getContrastTextColor($congregacao->config->conjunto_cores['terciaria']); ?>
            --primary-color: {{$congregacao->config->conjunto_cores['primaria'] ?? '#677b96'}};
            --secondary-color: {{$congregacao->config->conjunto_cores['secundaria'] ?? '#0a1929'}};
            --terciary-color: {{$congregacao->config->conjunto_cores['terciaria'] ?? '#f44916'}};
            --background-color: {{$congregacao->config->conjunto_cores['fundo'] ?? 'ffffff'}};
            --text-color: {{$congregacao->config->conjunto_cores['texto'] ?? '000000'}};
            --primary-contrast: {{ getContrastTextColor($congregacao->config->conjunto_cores['primaria'])}};
            --secondary-contrast: {{ getContrastTextColor($congregacao->config->conjunto_cores['secundaria'])}};
            --terciary-contrast: {{ getContrastTextColor($congregacao->config->conjunto_cores['terciaria'])}};

            --text-font: {{$congregacao->config->font_family}};
        }
        </style>
    </head>
    <body>
        <div class="popup">
            <h2>Aviso</h2>
            <p id="msg_content"></p>
            <button id="confirmaBtn"><i class="bi bi-check"></i> Confirmar</button>
            <button id="cancelaBtn"><i class="bi bi-x"></i> Cancelar</button> 
        </div>
        <header class="nao-imprimir">
            <nav class="main-navbar">
                <div class="nav-logo">
                    <img src="{{asset($congregacao->config->logo_caminho)}}" alt="{{$congregacao->denominacao->nome}} Logo">
                </div>
                <div class="nav-menu">
                    <ul>
                        <a href="/"><li><i class="bi bi-kanban"></i> Controle</li></a>
                        <a href="/membros/painel"><li><i class="bi bi-people"></i> Membros</li></a>
                        <a href="/cadastros"><li><i class="bi bi-journals"></i> Cadastros</li></a>
                        <a href="/visitantes/adicionar"><li><i class="bi bi-people-fill"></i> Visitantes</li></a>
                    </ul>
                </div>
            </nav>
        </header>
        <main>
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
            <nav class="left-navbar">
                <div class="menu-btn">
                    <span title="Tutoriais" id="btn-tutorial"><i class="bi bi-question-octagon"></i></span>
                    <span title="Configurações" id="btn-config"><i class="bi bi-gear"></i></span>
                    <span title="Menu Principal" id="btn-menu"><i class="bi bi-list"></i></span>
                </div>
                <ul class="menu-content">
                    <li><span title="Controle"><i class="bi bi-kanban"></i></span><span>Controle Geral</span></li>
                    <li><span title="Membros"><i class="bi bi-people"></i></span>Membros</span></li>
                    <li><span title="Eventos"><i class="bi bi-calendar-event"></i></span><span>Eventos</span></li>
                    <li><span title="Cultos"><i class="bi bi-bell"></i></span><span>Cultos</span></li>
                    <li><span title="Visitantes"><i class="bi bi-people-fill"></i></span><span>Visitantes</span></li>
                    <li><span title="Departamentos"><i class="bi bi-intersect"></i></span><span>Departamentos</span></li>
                    <li><span title="GCA - Células"><i class="bi bi-cup-hot"></i></span><span>GCA - Células</span></li>
                    <li><span title="Financeiro"><i class="bi bi-currency-exchange"></i></span><span>Financeiro</span></li>
                    <li><span title="Notícias"><i class="bi bi-newspaper"></i></span><span>Notícias</span></li>
                    <li><span title="Recados"><i class="bi bi-chat-left-dots"></i></span><span>Recados</span></li>
                    <li><span title="Tutoriais"><i class="bi bi-question-octagon"></i></span><span>Tutoriais</span></li>
                    <li><span title="Plugins"><i class="bi bi-nut"></i></span><span>Plugins</span></li>
                    <li><span title="Configurações"><i class="bi bi-gear"></i></span><span>Configurações</span></li>
                </ul>
            </nav>
            @yield('content')
        </main>
        <footer>
            <p>Sistema de Gestão Interna | AD Jerusalém - Ilha Solteira/SP</p>
            <h4>CNPJ 53.677.184/0001-95</h4>
        </footer>

        <!--Aqui vão os scripts que serão usados-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

        <!-- Exemplo de input : <input type="tel" id="telefone" placeholder="(00) 00000-0000"> -->

        <script>
            $(document).ready(function(){
            
                $('#telefone').mask('(00) 00000-0000');
                $('#cep').mask('00000-000');
                
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
                            width: '260px'
                        },200)
                    }

                    //Alterar o valor do Toggle
                    menuToggle = !menuToggle;

                })
            });

        </script>
        
        @stack('scripts')
    </body>
</html>
