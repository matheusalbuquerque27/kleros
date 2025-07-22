@extends('layouts.kleros')
@section('title', 'Kleros - Administração Geral')

@section('content')
<body>
    <main>
        <section class="container">
            <div class="info">
                <h2>Painel Administrativo</h2>
                <p>Gerencie as denominações cadastradas deste Saas.</p>
            </div>
        
            <div class="info_item">
                <div class="info">
                    <h3>Estatísticas do sistema</h3>
                    <ul>
                        <li>Total de Denominações: {{count($denominacoes)}}</li>
                        <li>Total de Congregações: {{count($congregacoes)}}</li>
                        <li>Membros ativos: 0</li>
                        <li>Domínios ativos: {{count($dominios)}}</li>
                    </ul>
                </div>
            </div>
        </section>
    </main>
@endsection