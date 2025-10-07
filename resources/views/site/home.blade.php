@extends('layouts.site')

@section('title', 'Kleros — Ecossistema para Igrejas')

@section('content')
<div class="min-h-screen bg-[#1a1821] text-[#f4f3f6] font-[Segoe_UI,Roboto,system-ui,-apple-system,Arial,sans-serif]">
    {{-- HEADER --}}
    <header class="sticky top-0 z-50 bg-[#1a1821]/90 backdrop-blur border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="{{ route('site.home') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/kleros-logo.svg') }}" alt="Kleros" class="h-8 w-auto">
                <div class="leading-tight">
                    <span class="font-semibold text-lg">Kleros</span>
                    <span class="block text-xs text-white/60">por Youcan Serviços Empresariais</span>
                </div>
            </a>

            <nav class="hidden md:flex gap-8 text-sm">
                <a href="#recursos" class="hover:text-white">Recursos</a>
                <a href="#extensoes" class="hover:text-white">Extensões</a>
                <a href="#ecossistema" class="hover:text-white">Ecossistema</a>
                <a href="#precos" class="hover:text-white">Preço</a>
                <a href="#faq" class="hover:text-white">FAQ</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="#demo" class="hidden sm:inline-flex px-4 py-2 rounded-lg border border-white/20 hover:border-white/40 text-sm">Ver demonstração</a>
                <a href="{{ route('congregacoes.cadastro') }}" class="px-4 py-2 rounded-lg bg-[#6449a2] hover:bg-[#584091] text-sm font-medium shadow-md">Começar agora</a>
            </div>
        </div>
    </header>

    {{-- HERO --}}
    <section class="max-w-7xl mx-auto px-4 py-20 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <h1 class="text-4xl md:text-5xl font-bold leading-tight">Unidade que alcança. Gestão que transforma.</h1>
            <p class="mt-4 text-lg text-white/80">O Kleros é um ecossistema moderno e completo para igrejas: gestão de cultos, eventos, membros, grupos e finanças — com gráficos, agenda, notificações e relatórios. Expanda com extensões e integre sua denominação em uma só plataforma web e mobile.</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('congregacoes.cadastro') }}" class="inline-flex items-center px-5 py-3 rounded-lg bg-[#6449a2] hover:bg-[#584091] font-medium">Assinar por R$110/mês</a>
                <a href="#recursos" class="inline-flex items-center px-5 py-3 rounded-lg border border-white/15 hover:border-white/30">Explorar recursos</a>
            </div>
            <p class="mt-3 text-xs text-white/60">Subdomínio imediato: <span class="font-mono">suaigreja.kleros.com.br</span>. Domínio próprio opcional.</p>
        </div>

        <div class="bg-white/5 border border-white/10 p-5 rounded-2xl">
            <div class="grid grid-cols-3 gap-3">
                <div class="col-span-2 bg-white/10 h-40 rounded-lg"></div>
                <div class="bg-white/10 h-40 rounded-lg"></div>
            </div>
        </div>
    </section>

    {{-- PROPOSTA MISSIONÁRIA --}}
    <section id="proposito" class="py-16 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-3 gap-8">
            <div>
                <h2 class="text-2xl font-semibold">Tecnologia com propósito</h2>
                <p class="mt-3 text-white/80">Nascido dentro da agência missionária <strong>Globus Dei</strong>, o Kleros destina parte generosa do negócio para apoiar obras e irmãos missionários. Gestão que organiza, integração que aproxima e investimento que alcança.</p>
            </div>
            <div class="md:col-span-2 grid sm:grid-cols-3 gap-6">
                <div class="bg-white/5 p-5 rounded-xl border border-white/10">
                    <h3 class="font-semibold">Apoio ao campo missionário</h3>
                    <p class="text-white/80 text-sm mt-2">Seu investimento fortalece frentes missionárias e projetos locais.</p>
                </div>
                <div class="bg-white/5 p-5 rounded-xl border border-white/10">
                    <h3 class="font-semibold">Comunidade mais próxima</h3>
                    <p class="text-white/80 text-sm mt-2">Conteúdo missionário e conexões entre membros e congregações.</p>
                </div>
                <div class="bg-white/5 p-5 rounded-xl border border-white/10">
                    <h3 class="font-semibold">Ecossistema expandível</h3>
                    <p class="text-white/80 text-sm mt-2">Extensões instaláveis elevam a gestão local e da denominação.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- RECURSOS PRINCIPAIS --}}
    <section id="recursos" class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-2xl font-semibold">Tudo que sua igreja precisa</h2>
            <p class="text-white/80 mt-2 max-w-3xl">Gestão de eventos e cultos, membros e visitantes, reuniões, grupos, departamentos, setores e financeiro — com gráficos, agenda, notificações, relatórios e apps web e mobile.</p>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                @php
                    $features = [
                        'Eventos & Cultos' => 'Programação, inscrições, equipes e relatórios.',
                        'Membros & Visitantes' => 'Cadastro completo e comunicação segmentada.',
                        'Grupos & Ministérios' => 'Departamentos, setores e times organizados.',
                        'Financeiro' => 'Entradas, saídas e gráficos de saúde financeira.',
                        'Agenda & Calendário' => 'Visual semanal e mensal integrado.',
                        'Relatórios' => 'Indicadores e estatísticas em tempo real.'
                    ];
                @endphp
                @foreach($features as $title => $desc)
                    <div class="bg-white/5 p-6 rounded-xl border border-white/10 hover:bg-white/10">
                        <h3 class="font-semibold">{{ $title }}</h3>
                        <p class="mt-2 text-white/80 text-sm">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- EXTENSÕES --}}
    <section id="extensoes" class="py-16 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-2xl font-semibold">Extensões que elevam sua gestão</h2>
            <p class="text-white/80 mt-2">Instale módulos internos conforme a necessidade da sua igreja — tudo integrado e pronto para uso.</p>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                @foreach(['Células','Recados no Culto','Gestor de Arquivos','Grupos Esportivos','Livraria (in/out)','Loja de Recursos (em breve)','Gestão de Projetos (em breve)','Pesquisas Internas (em breve)'] as $ext)
                    <div class="bg-white/5 p-6 rounded-xl border border-white/10 hover:bg-white/10">
                        <h3 class="font-semibold">{{ $ext }}</h3>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- PREÇO --}}
    <section id="precos" class="py-16 border-t border-white/10">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <h2 class="text-2xl font-semibold">Preço simples, atendimento personalizado</h2>
            <p class="text-white/80 mt-2">Plano inicial com tudo que você precisa para começar bem. Escale com extensões quando desejar.</p>
            <div class="grid md:grid-cols-2 gap-6 mt-10">
                <div class="bg-white/5 p-8 rounded-xl border border-white/10">
                    <h3 class="text-lg font-semibold">Plano Inicial</h3>
                    <p class="text-4xl font-bold mt-2">R$110<span class="text-lg">/mês</span></p>
                    <ul class="mt-4 text-white/80 space-y-1 text-sm">
                        <li>✔ Eventos, cultos e membros</li>
                        <li>✔ Reuniões e grupos</li>
                        <li>✔ Financeiro e relatórios</li>
                        <li>✔ Agenda e notificações</li>
                        <li>✔ Subdomínio imediato</li>
                    </ul>
                </div>
                <div class="bg-white/5 p-8 rounded-xl border border-white/10">
                    <h3 class="text-lg font-semibold">Sob medida</h3>
                    <p class="text-white/80 mt-2">Para convenções e redes com múltiplas igrejas.</p>
                    <a href="#contato" class="inline-block mt-5 px-5 py-3 rounded-lg border border-white/15 hover:border-white/30">Falar com especialista</a>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA FINAL --}}
    <section id="assinar" class="py-20 text-center">
        <h2 class="text-2xl font-semibold">Pronto para organizar e integrar sua igreja?</h2>
        <p class="text-white/80 mt-3">Assine por R$110/mês e receba seu subdomínio imediatamente.</p>
        <div class="mt-6 flex justify-center gap-4">
            <a href="#contato" class="px-5 py-3 rounded-lg bg-[#6449a2] hover:bg-[#584091] font-medium">Falar com a Youcan</a>
            <a href="#demo" class="px-5 py-3 rounded-lg border border-white/15 hover:border-white/30">Ver demonstração</a>
        </div>
    </section>

    {{-- RODAPÉ --}}
    <footer class="border-t border-white/10 py-10 text-center text-sm text-white/70">
        <p>Kleros — Ecossistema para Igrejas. Desenvolvido por <strong>Youcan Serviços Empresariais</strong>.</p>
        <p class="text-white/40 mt-2">© {{ date('Y') }} Todos os direitos reservados.</p>
    </footer>
</div>
@endsection
