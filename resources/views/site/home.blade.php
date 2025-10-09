@extends('layouts.site')

@section('title', __('site.meta.title'))
@section('meta_description', __('site.meta.description'))

@section('content')
@php
    $currentLocale = app()->getLocale();
    $missionCards = trans('site.mission.cards');
    $featureItems = trans('site.features.items');
    $extensionItems = trans('site.extensions.items');
    $starterItems = trans('site.pricing.plans.starter.items');

    $subdomainHints = [
        'pt' => 'suaigreja.kleros.com.br',
        'en' => 'yourchurch.kleros.com.br',
        'es' => 'tuiglesia.kleros.com.br',
    ];

    $subdomainExample = $subdomainHints[$currentLocale] ?? 'yourchurch.kleros.com.br';
@endphp
<div class="min-h-screen bg-[#1a1821] text-[#f4f3f6] font-[Segoe_UI,Roboto,system-ui,-apple-system,Arial,sans-serif]">
    {{-- HEADER --}}
    <header class="sticky top-0 z-50 bg-[#1a1821]/90 backdrop-blur border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between gap-4">
            <a href="{{ route('site.home') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/kleros-logo.svg') }}" alt="Kleros" class="h-8 w-auto">
                <div class="leading-tight">
                    <span class="font-semibold text-lg">Kleros</span>
                    <span class="block text-xs text-white/60">{{ __('site.header.brand_tagline') }}</span>
                </div>
            </a>

            <nav class="hidden md:flex gap-8 text-sm">
                <a href="#recursos" class="hover:text-white">{{ __('site.header.nav.resources') }}</a>
                <a href="#extensoes" class="hover:text-white">{{ __('site.header.nav.extensions') }}</a>
                <a href="#proposito" class="hover:text-white">{{ __('site.header.nav.ecosystem') }}</a>
                <a href="#precos" class="hover:text-white">{{ __('site.header.nav.pricing') }}</a>
                <a href="#assinar" class="hover:text-white">{{ __('site.header.nav.faq') }}</a>
            </nav>

            <div class="flex items-center gap-3">
                @include('site.partials.language-switcher', ['formClass' => 'hidden sm:block', 'selectId' => 'locale-home'])
                <a href="#demo" class="hidden sm:inline-flex px-4 py-2 rounded-lg border border-white/20 hover:border-white/40 text-sm">
                    {{ __('site.header.demo') }}
                </a>
                <a href="{{ route('congregacoes.cadastro') }}" class="px-4 py-2 rounded-lg bg-[#6449a2] hover:bg-[#584091] text-sm font-medium shadow-md">
                    {{ __('site.header.cta') }}
                </a>
            </div>
        </div>
    </header>

    {{-- HERO --}}
    <section id="hero" class="max-w-7xl mx-auto px-4 py-20 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <h1 class="text-4xl md:text-5xl font-bold leading-tight">{{ __('site.hero.title') }}</h1>
            <p class="mt-4 text-lg text-white/80">{{ __('site.hero.description') }}</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('congregacoes.cadastro') }}" class="inline-flex items-center px-5 py-3 rounded-lg bg-[#6449a2] hover:bg-[#584091] font-medium">
                    {{ __('site.hero.primary_cta') }}
                </a>
                <a href="#recursos" class="inline-flex items-center px-5 py-3 rounded-lg border border-white/15 hover:border-white/30">
                    {{ __('site.hero.secondary_cta') }}
                </a>
            </div>
            <p class="mt-3 text-xs text-white/60">{!! __('site.hero.subdomain_hint', ['subdomain' => '<span class="font-mono">' . $subdomainExample . '</span>']) !!}</p>
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
                <h2 class="text-2xl font-semibold">{{ __('site.mission.title') }}</h2>
                <p class="mt-3 text-white/80">{!! __('site.mission.description') !!}</p>
            </div>
            <div class="md:col-span-2 grid sm:grid-cols-3 gap-6">
                @foreach($missionCards as $card)
                    <div class="bg-white/5 p-5 rounded-xl border border-white/10">
                        <h3 class="font-semibold">{{ $card['title'] }}</h3>
                        <p class="text-white/80 text-sm mt-2">{{ $card['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- RECURSOS PRINCIPAIS --}}
    <section id="recursos" class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-2xl font-semibold">{{ __('site.features.title') }}</h2>
            <p class="text-white/80 mt-2 max-w-3xl">{{ __('site.features.description') }}</p>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                @foreach($featureItems as $feature)
                    <div class="bg-white/5 p-6 rounded-xl border border-white/10 hover:bg-white/10">
                        <h3 class="font-semibold">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-white/80 text-sm">{{ $feature['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- EXTENSÕES --}}
    <section id="extensoes" class="py-16 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-2xl font-semibold">{{ __('site.extensions.title') }}</h2>
            <p class="text-white/80 mt-2">{{ __('site.extensions.description') }}</p>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                @foreach($extensionItems as $ext)
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
            <h2 class="text-2xl font-semibold">{{ __('site.pricing.title') }}</h2>
            <p class="text-white/80 mt-2">{{ __('site.pricing.description') }}</p>
            <div class="grid md:grid-cols-2 gap-6 mt-10 text-left">
                <div class="bg-white/5 p-8 rounded-xl border border-white/10">
                    <h3 class="text-lg font-semibold">{{ __('site.pricing.plans.starter.name') }}</h3>
                    <p class="text-4xl font-bold mt-2">{{ __('site.pricing.plans.starter.price') }}</p>
                    <ul class="mt-4 text-white/80 space-y-1 text-sm">
                        @foreach($starterItems as $item)
                            <li>✔ {{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="bg-white/5 p-8 rounded-xl border border-white/10">
                    <h3 class="text-lg font-semibold">{{ __('site.pricing.plans.custom.name') }}</h3>
                    <p class="text-white/80 mt-2">{{ __('site.pricing.plans.custom.description') }}</p>
                    <a href="#contato" class="inline-block mt-5 px-5 py-3 rounded-lg border border-white/15 hover:border-white/30">
                        {{ __('site.pricing.plans.custom.cta') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA FINAL --}}
    <section id="assinar" class="py-20 text-center">
        <h2 class="text-2xl font-semibold">{{ __('site.cta.title') }}</h2>
        <p class="text-white/80 mt-3">{{ __('site.cta.description') }}</p>
        <div class="mt-6 flex justify-center gap-4 flex-wrap">
            <a href="#contato" class="px-5 py-3 rounded-lg bg-[#6449a2] hover:bg-[#584091] font-medium">
                {{ __('site.cta.primary') }}
            </a>
            <a href="#demo" class="px-5 py-3 rounded-lg border border-white/15 hover:border-white/30">
                {{ __('site.cta.secondary') }}
            </a>
        </div>
    </section>

    {{-- RODAPÉ --}}
    <footer class="border-t border-white/10 py-10 text-center text-sm text-white/70">
        <p>{!! __('site.footer.legal') !!}</p>
        <p class="text-white/40 mt-2">{{ __('site.footer.rights', ['year' => date('Y')]) }}</p>
    </footer>
</div>
@endsection
