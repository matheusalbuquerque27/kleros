<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kleros - Integração de Igrejas</title>
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="/css/admin.css">
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
                <img src="/images/logo.png" alt="">
            </div>
            <div class="nav-menu">
                <ul>
                    <a href="/"><li><i class="bi bi-clipboard-data"></i> Dashboard</li></a>
                    <a href="/membros/painel"><li><i class="bi bi-list-task"></i> Demandas</li></a>
                    <a href="/cadastros"><li><i class="bi bi-box"></i> Módulos</li></a>
                    <a href="/visitantes/adicionar"><li><i class="bi bi-gear"></i> Configurações</li></a>
                    <a href="{{route('logout')}}"><li><i class="bi bi-box-arrow-right"></i> Sair</li></a>
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
        <p>Plataforma de Gerenciamento e Integração de Igrejas</p>
        <h4>CNPJ 53.677.184/0001-95</h4>
    </footer>
    <script src="/js/script.js"></script>
</body>
</html>