<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AD Jerusalém</title>
    <link rel="stylesheet" href="/css/login.css">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teko" rel="stylesheet">
</head>

<body class="body-login">
    <div class="login-container">
        <img src="/images/logo.png" alt="AD Jerusalém - Ilha Solteira">
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


