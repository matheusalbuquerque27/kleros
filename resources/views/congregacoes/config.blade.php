@extends('layouts.site')

@section('title', __('congregations.meta.config_title'))
@section('meta_description', __('site.meta.description'))

@section('content')
@php
    $configTexts = trans('congregations.config');
    $identitySection = $configTexts['sections']['identity'];
    $colorSection = $configTexts['sections']['colors'];
    $moduleSection = $configTexts['sections']['modules'];
    $groupingOptions = $moduleSection['grouping_options'];
@endphp
<div class="min-h-screen bg-[#1a1821] text-[#f4f3f6] font-[Segoe_UI,Roboto,system-ui,-apple-system,Arial,sans-serif]">
    <header class="sticky top-0 z-40 bg-[#1a1821]/95 border-b border-white/10">
        <div class="max-w-5xl mx-auto px-4 h-16 flex items-center justify-between gap-3">
            <a href="{{ route('site.home') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/kleros-logo.svg') }}" alt="Kleros" class="h-8 w-auto">
                <div class="leading-tight">
                    <span class="font-semibold text-lg">Kleros</span>
                    <span class="block text-xs text-white/60">{{ __('congregations.header.tagline') }}</span>
                </div>
            </a>
            <div class="flex items-center gap-3">
                <span class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-white/20 text-sm text-white/80">
                    {{ __('congregations.header.progress') }}
                </span>
                @include('site.partials.language-switcher', ['formClass' => 'hidden sm:block', 'selectId' => 'locale-congregations-config'])
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-16">
        <div class="text-center md:text-left space-y-3">
            <span class="uppercase tracking-[0.2em] text-xs text-white/50">{{ $configTexts['badge'] }}</span>
            <h1 class="text-3xl md:text-4xl font-semibold">{{ $configTexts['title'] }}</h1>
            <p class="text-white/70 text-base md:text-lg">{{ $configTexts['description'] }}</p>
        </div>

        @if (session('config_intro'))
            <div class="mt-8 rounded-xl border border-white/15 bg-white/5 px-4 py-3 text-sm text-white/75">
                {{ session('config_intro') }}
            </div>
        @endif

        @if (session('msg'))
            <div class="mt-8 rounded-xl border border-emerald-400/40 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-200">
                {{ session('msg') }}
            </div>
            <div class="mt-6 flex flex-col gap-4 rounded-2xl border border-white/10 bg-white/5 px-5 py-5 md:flex-row md:items-center md:justify-between">
                <div>
                    <span class="uppercase tracking-[0.18em] text-xs text-white/50">{{ $configTexts['next_steps']['title'] }}</span>
                    <p class="mt-2 text-sm text-white/75">{{ $configTexts['next_steps']['description'] }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('congregacoes.cadastro') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 px-4 py-2 text-xs font-semibold text-white/80 hover:border-white/40">
                        <i class="bi bi-arrow-left"></i> {{ $configTexts['next_steps']['back'] }}
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#6449a2] px-4 py-2 text-xs font-semibold text-white shadow-lg shadow-[#6449a2]/40 hover:bg-[#8261c2]">
                        <i class="bi bi-box-arrow-in-right"></i> {{ $configTexts['next_steps']['login'] }}
                    </a>
                </div>
            </div>
        @else
        <div class="mt-8 space-y-4">
            @if ($errors->any())
                <div class="rounded-xl border border-rose-400/40 bg-rose-400/10 px-4 py-3 text-sm text-rose-200">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        @php
            $configCongregacao = $congregacao ?? $config?->congregacao;
            $configRouteId = optional($configCongregacao)->id;
        @endphp
        <form id="congregacao-config-form" action="{{ $configRouteId ? route('congregacoes.config.salvar', $configRouteId) : '#' }}" method="POST" enctype="multipart/form-data" class="mt-10 bg-white/5 border border-white/10 rounded-3xl p-8 space-y-12">
            @csrf
            <input type="hidden" name="congregacao_id" value="{{ $configRouteId }}">
            <section class="space-y-6">
                <div>
                    <span class="uppercase tracking-[0.18em] text-xs text-white/50">{{ $identitySection['badge'] }}</span>
                    <h2 class="mt-2 text-xl font-semibold">{{ $identitySection['title'] }}</h2>
                    <p class="text-white/60 text-sm mt-2">{{ $identitySection['description'] }}</p>
                </div>
                <div class="grid gap-6 md:grid-cols-2">
                    <label class="block">
                        <span class="text-sm font-medium text-white/80">{{ $identitySection['logo_label'] }}</span>
                        <div class="mt-3 flex items-center gap-4 rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="h-16 w-16 shrink-0 rounded-xl bg-white/10 flex items-center justify-center overflow-hidden">
                                @if ($config->logo_caminho)
                                    <img src="{{ asset('storage/' . $config->logo_caminho) }}" alt="Logo" class="h-full w-full object-cover">
                                @else
                                    <i class="bi bi-image text-2xl text-white/40"></i>
                                @endif
                            </div>
                            <div class="flex-1 space-y-2">
                                <span id="logo-filename" class="block text-sm text-white/60">{{ $identitySection['logo_placeholder'] }}</span>
                                <label class="inline-flex items-center gap-2 rounded-lg border border-white/20 px-3 py-2 text-sm text-white/80 hover:border-white/50 cursor-pointer">
                                    <i class="bi bi-upload"></i> {{ $identitySection['upload'] }}
                                    <input type="file" name="logo" id="logo" class="hidden" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </label>

                    <label class="block">
                        <span class="text-sm font-medium text-white/80">{{ $identitySection['banner_label'] }}</span>
                        <div class="mt-3 flex items-center gap-4 rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="h-16 w-24 shrink-0 rounded-xl bg-white/10 flex items-center justify-center overflow-hidden">
                                @if ($config->banner_caminho)
                                    <img src="{{ asset('storage/' . $config->banner_caminho) }}" alt="Banner" class="h-full w-full object-cover">
                                @else
                                    <i class="bi bi-images text-2xl text-white/40"></i>
                                @endif
                            </div>
                            <div class="flex-1 space-y-2">
                                <span id="banner-filename" class="block text-sm text-white/60">{{ $identitySection['banner_placeholder'] }}</span>
                                <label class="inline-flex items-center gap-2 rounded-lg border border-white/20 px-3 py-2 text-sm text-white/80 hover:border-white/50 cursor-pointer">
                                    <i class="bi bi-upload"></i> {{ $identitySection['upload'] }}
                                    <input type="file" name="banner" id="banner" class="hidden" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </label>
                </div>
            </section>

            <section class="space-y-6">
                <div>
                    <span class="uppercase tracking-[0.18em] text-xs text-white/50">{{ $colorSection['badge'] }}</span>
                    <h2 class="mt-2 text-xl font-semibold">{{ $colorSection['title'] }}</h2>
                    <p class="text-white/60 text-sm mt-2">{{ $colorSection['description'] }}</p>
                </div>
                <div class="grid gap-5 md:grid-cols-3">
                    <label class="block">
                        <span class="text-sm font-medium text-white/80">{{ $colorSection['fields']['primary'] }}</span>
                        <input type="color" name="conjunto_cores[primaria]" value="{{ $config->conjunto_cores['primaria'] ?? '#6449a2' }}" class="mt-3 h-12 w-full rounded-xl border border-white/10 bg-white/5 cursor-pointer">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-white/80">{{ $colorSection['fields']['secondary'] }}</span>
                        <input type="color" name="conjunto_cores[secundaria]" value="{{ $config->conjunto_cores['secundaria'] ?? '#1a1821' }}" class="mt-3 h-12 w-full rounded-xl border border-white/10 bg-white/5 cursor-pointer">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-white/80">{{ $colorSection['fields']['accent'] }}</span>
                        <input type="color" name="conjunto_cores[terciaria]" value="{{ $config->conjunto_cores['terciaria'] ?? '#cbb6ff' }}" class="mt-3 h-12 w-full rounded-xl border border-white/10 bg-white/5 cursor-pointer">
                    </label>
                </div>
                <div class="grid gap-5 md:grid-cols-2">
                    <label class="block">
                        <span class="text-sm font-medium text-white/80">{{ $colorSection['fields']['font'] }}</span>
                        <select name="font_family" class="mt-3 w-full rounded-xl bg-white text-[#1a1821] border border-white/15 px-4 py-3 focus:border-[#6449a2] focus:outline-none focus:ring-2 focus:ring-[#6449a2]/40">
                            @foreach($fontes as $fonte)
                                <option value="{{ $fonte }}" @selected($config->font_family === $fonte)>{{ $fonte }}</option>
                            @endforeach
                        </select>
                    </label>
                    <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-4">
                        <span class="text-xs uppercase tracking-[0.18em] text-white/50">{{ $colorSection['fields']['preview_badge'] }}</span>
                        <p class="mt-3 text-white/80 text-sm font-medium">{{ $colorSection['fields']['preview_quote'] }}</p>
                        <p class="text-white/50 text-xs mt-1">{{ $config->font_family }}</p>
                    </div>
                </div>
            </section>

            <section class="space-y-6">
                <div>
                    <span class="uppercase tracking-[0.18em] text-xs text-white/50">{{ $moduleSection['badge'] }}</span>
                    <h2 class="mt-2 text-xl font-semibold">{{ $moduleSection['title'] }}</h2>
                    <p class="text-white/60 text-sm mt-2">{{ $moduleSection['description'] }}</p>
                </div>
                <div class="space-y-6">
                    <div class="grid gap-4 md:grid-cols-3">
                        @foreach($temas as $tema)
                            <label class="rounded-2xl border border-white/15 bg-white/5 p-4 flex flex-col gap-3 cursor-pointer hover:border-white/40 transition">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium">{{ $tema->nome }}</span>
                                    <input type="radio" name="tema_id" value="{{ $tema->id }}" @checked($config->tema_id == $tema->id)>
                                </div>
                                <div class="flex gap-2">
                                    @if(is_array($tema->propriedades))
                                        @foreach($tema->propriedades['amostras'] ?? [] as $cor)
                                            <span class="h-6 w-6 rounded-full border border-white/10" style="background: {{ $cor }};"></span>
                                        @endforeach
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <label class="block">
                            <span class="text-sm font-medium text-white/80">{{ $moduleSection['grouping'] }}</span>
                            <select name="agrupamentos" class="mt-3 w-full rounded-xl bg-white text-[#1a1821] border border-white/15 px-4 py-3 focus:border-[#6449a2] focus:outline-none focus:ring-2 focus:ring-[#6449a2]/40">
                                @foreach($groupingOptions as $key => $label)
                                    <option value="{{ $key }}" @selected($config->agrupamentos === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                        <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-4 flex items-center justify-between">
                            <div>
                                <span class="text-xs uppercase tracking-[0.18em] text-white/50">{{ $moduleSection['cells']['title'] }}</span>
                                <p class="text-white/75 text-sm font-medium mt-1">{{ $moduleSection['cells']['question'] }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <label class="inline-flex items-center gap-2 text-sm">
                                    <input type="radio" name="celulas" value="1" @checked($config->celulas) class="text-[#6449a2] focus:ring-[#6449a2]">
                                    {{ $moduleSection['cells']['active'] }}
                                </label>
                                <label class="inline-flex items-center gap-2 text-sm">
                                    <input type="radio" name="celulas" value="0" @checked(!$config->celulas) class="text-[#6449a2] focus:ring-[#6449a2]">
                                    {{ $moduleSection['cells']['inactive'] }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <a href="{{ route('congregacoes.cadastro') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 px-4 py-3 text-sm text-white/80 hover:border-white/40">
                    <i class="bi bi-arrow-left"></i> {{ $configTexts['buttons']['back'] }}
                </a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#6449a2] px-5 py-3 text-sm font-medium text-white shadow-lg shadow-[#6449a2]/40 hover:bg-[#8261c2]">
                    <i class="bi bi-check-circle"></i> {{ $configTexts['buttons']['submit'] }}
                </button>
            </div>
        </form>
        @endif
    </main>
</div>

@push('scripts')
<script>
    const fileInputs = [
        { input: 'logo', label: 'logo-filename' },
        { input: 'banner', label: 'banner-filename' },
    ];

    fileInputs.forEach(({ input, label }) => {
        const inputEl = document.getElementById(input);
        const labelEl = document.getElementById(label);

        if (inputEl && labelEl) {
            inputEl.addEventListener('change', (event) => {
                const [file] = event.target.files;
                labelEl.textContent = file ? file.name : '{{ $configTexts['file_placeholder'] }}';
            });
        }
    });
</script>
@endpush
@endsection
