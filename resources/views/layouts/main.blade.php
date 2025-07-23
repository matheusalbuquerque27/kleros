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
            --primary-color: {{$congregacao->config->conjunto_cores['primaria'] ?? '#677b96'}};
            --secondary-color: {{$congregacao->config->conjunto_cores['secundaria'] ?? '#0a1929'}};
            --terciary-color: {{$congregacao->config->conjunto_cores['terciaria'] ?? '#f44916'}};
            --background-color: {{$congregacao->config->conjunto_cores['fundo'] ?? 'ffffff'}};
            --text-color: {{$congregacao->config->conjunto_cores['texto'] ?? '000000'}};

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
                        <a href="/"><li>Controle</li></a>
                        <a href="/membros/painel"><li>Membros</li></a>
                        <a href="/cadastros"><li>Cadastros</li></a>
                        <a href="/visitantes/adicionar"><li>Visitantes</li></a>
                    </ul>
                </div>
            </nav>
        </header>
        <main>
            @if (session('msg'))
                <div class="msg">
                    <div class="success"> {{ session('msg') }}</div>
                </div>
            @endif
            @if (session('msg-error'))
                <div class="msg">
                    <div class="error"> {{ session('msg-error') }}</div>
                </div>
            @endif
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
           
            });
        </script>
        
        @stack('scripts')
    </body>
</html>
