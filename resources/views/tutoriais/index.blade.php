@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')

<div class="container">
    <h1>Tutoriais</h1>
    <div class="info">
        <h3>Conheça seu sistema</h3>
        <p>Este guia provisório apresenta as principais áreas do sistema para que você possa navegar com segurança enquanto os tutoriais completos não ficam prontos.</p>
        <br>
        <div class="card-container tutorials-list">
            <div class="card">
                <div class="card-title">Primeiros passos</div>
                <div class="card-description">
                    <p>Acesse o painel inicial para visualizar pendências e atalhos gerais.</p>
                    <p>
                        Atualize seus dados em <a href="{{ route('perfil') }}">Perfil</a>
                        @if($congregacao)
                            e ajuste permissões da congregação em <a href="{{ route('configuracoes.editar', $congregacao->id) }}">Configurações</a>
                        @endif
                        para manter o acesso alinhado às necessidades da equipe.
                    </p>
                    <p>Use o menu lateral ou os atalhos no topo para alternar entre módulos rapidamente.</p>
                </div>
                <a href="{{ route('index') }}" class="btn mg-top-10">Abrir painel inicial</a>
            </div>

            <div class="card">
                <div class="card-title">Cadastros essenciais</div>
                <div class="card-description">
                    <p>Mantenha membros, visitantes e equipes atualizados em <a href="{{ route('cadastros.index') }}">Cadastros</a>.</p>
                    <p>Inclua novos cultos, eventos e reuniões diretamente pelos botões de ação.</p>
                    <p>Atualize lideranças, departamentos e ministérios para manter a agenda e relatórios consistentes.</p>
                </div>
                <a href="{{ route('cadastros.index') }}" class="btn mg-top-10">Organizar cadastros</a>
            </div>

            <div class="card">
                <div class="card-title">Agenda integrada</div>
                <div class="card-description">
                    <p>Visualize cultos, eventos e reuniões na <a href="{{ route('agenda.index') }}">Agenda</a> em visão mensal ou anual.</p>
                    <p>Use os botões "Agendar" para criar novos registros sem sair da página.</p>
                    <p>Clique em um item para ver detalhes rápidos de título, data e horário.</p>
                </div>
                <a href="{{ route('agenda.index') }}" class="btn mg-top-10">Abrir agenda</a>
            </div>

            <div class="card">
                <div class="card-title">Comunicação e conteúdo</div>
                <div class="card-description">
                    <p>Divulgue avisos internos em <a href="{{ route('avisos.painel') }}">Avisos</a> e acompanhe o que já foi enviado.</p>
                    <p>Publique notícias, destaques e podcasts pelo módulo <a href="{{ route('feeds.index') }}">Feeds</a>.</p>
                    <p>Centralize materiais de apoio em <a href="{{ route('arquivos.imagens') }}">Arquivos</a> e adicione planos de aula em <a href="{{ route('cursos.index') }}">Cursos</a>.</p>
                </div>
                <a href="{{ route('avisos.painel') }}" class="btn mg-top-10">Enviar aviso</a>
            </div>

            <div class="card">
                <div class="card-title">Análises e suporte</div>
                <div class="card-description">
                    <p>Gere documentos e estatísticas no módulo de <a href="{{ route('relatorios.painel') }}">Relatórios</a>.</p>
                    <p>Configure extensões e integrações adicionais em <a href="{{ route('extensoes.painel') }}">Extensões</a>.</p>
                    <p>Em caso de dúvidas, registre necessidades e acompanhe atualizações aqui na seção de tutoriais.</p>
                </div>
                <a href="{{ route('relatorios.painel') }}" class="btn mg-top-10">Acessar relatórios</a>
            </div>
        </div>

        <p class="mg-top-10">Em breve disponibilizaremos vídeos, checklists e artigos detalhados para cada funcionalidade. Aproveite este resumo para orientar novos usuários e padronizar os processos da sua congregação.</p>
    </div>
</div>

@endsection

@push('styles')
<style>
    .tutorials-list {
        flex-direction: column;
    }

    .tutorials-list .card {
        flex: unset;
        width: 96%;
    }

    .tutorials-list .card .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        margin-top: 24px;
    }
</style>
@endpush
