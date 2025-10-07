<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Kleros — Ecossistema de gestão e integração para igrejas, desenvolvido pela Youcan Serviços Empresariais.">
    <link rel="icon" href="{{asset('images/kleros-logo.svg')}}" type="image/svg+xml">
    <title>@yield('title', 'Kleros — Ecossistema para Igrejas')</title>

    {{-- Favicon (opcional) --}}
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

    {{-- Fonte principal --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <style>
        :root {
            --ui-font: "Segoe UI", Roboto, system-ui, -apple-system, Arial, sans-serif;
        }
        html {
            font-family: var(--ui-font);
            scroll-behavior: smooth;
            background-color: #1a1821;
            color: #f4f3f6;
        }
    </style>

    {{-- Tailwind CSS (se estiver usando Vite ou Mix) --}}
    @vite('resources/css/site.css')
</head>
<body class="antialiased">
    @yield('content')
    @stack('scripts')
</body>
</html>
