<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{$congregacao->denominacao->nome}}</title><
    <!-- SCSS managed by Vite -->
    @vite(['resources/css/app.scss'])
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teko" rel="stylesheet">

    <style>
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

<body id="login" style="background-image: url('{{ asset(optional($congregacao->config)?->banner_caminho) }}')">
    <div class="login-container">
        <img src="{{ asset(optional($congregacao->config)?->logo_caminho) }}" alt="{{$congregacao->denominacao->nome}}" class="logo">
        <h2>Login</h2>
        <div class="login-form">
            <form action="/login" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Usuário:</label>
                    <input type="text" name="name" id="name" required>
                </div>
                <div class="form-group">
                    <label for="password">Senha:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </div>
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </form>
        </div>
    </div>
</body>

</html>


