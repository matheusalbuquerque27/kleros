@php
    $congregacaoNome = $congregacao?->identificacao ?? config('app.name', 'Kleros');
@endphp

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <title>Código de recuperação de senha</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f3f4f6;
                color: #111827;
            }
            .wrapper {
                width: 100%;
                padding: 24px 0;
            }
            .container {
                max-width: 520px;
                margin: 0 auto;
                background: #ffffff;
                border-radius: 12px;
                box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
                padding: 32px;
                box-sizing: border-box;
            }
            h1 {
                font-size: 22px;
                margin-bottom: 16px;
            }
            p {
                line-height: 1.6;
                margin: 12px 0;
            }
            .code {
                font-size: 32px;
                font-weight: bold;
                letter-spacing: 8px;
                text-align: center;
                margin: 28px 0;
                color: #1d4ed8;
            }
            .footer {
                text-align: center;
                font-size: 12px;
                color: #6b7280;
                margin-top: 24px;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <div class="container">
                <h1>Olá, {{ $user->name }}!</h1>
                <p>Recebemos um pedido para redefinir a senha da sua conta no {{ $congregacaoNome }}.</p>
                <p>Utilize o código abaixo para continuar com a recuperação:</p>
                <div class="code">{{ $code }}</div>
                <p>Por motivos de segurança, este código expira em 15 minutos.</p>
                <p>Se você não solicitou a mudança de senha, pode ignorar este e-mail com segurança.</p>
                <p>Ficamos à disposição caso precise de ajuda.</p>
                <div class="footer">
                    &copy; {{ now()->year }} {{ $congregacaoNome }}. Todos os direitos reservados.
                </div>
            </div>
        </div>
    </body>
</html>
