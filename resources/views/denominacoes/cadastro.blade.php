@extends('layouts.site')

@section('title', __('denominations.meta.title'))
@section('meta_description', __('site.meta.description'))

@section('content')
@php
    $identity = trans('denominations.identity');
    $ministries = trans('denominations.ministries');
@endphp
<style>
    #base_doutrinaria {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(255, 255, 255, 0.15);
        color: #f4f3f6;
    }

    #base_doutrinaria:focus {
        border-color: #6449a2;
        outline: none;
        box-shadow: 0 0 0 2px rgba(100, 73, 162, 0.35);
    }

    #base_doutrinaria option {
        color: #1a1821;
        background: #f4f3f6;
    }
</style>
<div class="min-h-screen bg-[#1a1821] text-[#f4f3f6] font-[Segoe_UI,Roboto,system-ui,-apple-system,Arial,sans-serif]">
    <header class="sticky top-0 z-40 bg-[#1a1821]/95 border-b border-white/10">
        <div class="max-w-5xl mx-auto px-4 h-16 flex items-center justify-between gap-3">
            <a href="{{ route('site.home') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/kleros-logo.svg') }}" alt="Kleros" class="h-8 w-auto">
                <div class="leading-tight">
                    <span class="font-semibold text-lg">Kleros</span>
                    <span class="block text-xs text-white/60">{{ __('denominations.header.tagline') }}</span>
                </div>
            </a>
            <div class="flex items-center gap-3">
                @include('site.partials.language-switcher', ['formClass' => 'hidden sm:block', 'selectId' => 'locale-denominations'])
                <a href="{{ route('congregacoes.cadastro') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-white/20 hover:border-white/40 text-sm">
                    {{ __('denominations.header.link_label') }}
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-16">
        <div class="text-center md:text-left space-y-3">
            <span class="uppercase tracking-[0.2em] text-xs text-white/50">{{ __('denominations.hero.badge') }}</span>
            <h1 class="text-3xl md:text-4xl font-semibold">{{ __('denominations.hero.title') }}</h1>
            <p class="text-white/70 text-base md:text-lg">{{ __('denominations.hero.description') }}</p>
        </div>

        <div class="mt-8 space-y-4">
            @if (session('success'))
                <div class="rounded-xl border border-emerald-400/40 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

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

        <form action="{{ route('denominacoes.store') }}" method="POST" class="mt-10 bg-white/5 border border-white/10 rounded-3xl p-8 space-y-10">
            @csrf
            <div class="space-y-6">
                <div>
                    <h2 class="text-xl font-semibold">{{ $identity['title'] }}</h2>
                    <p class="text-white/60 text-sm mt-2">{{ $identity['subtitle'] }}</p>
                </div>
                <div class="grid md:grid-cols-1 gap-5">
                    <label class="block">
                        <span class="text-sm font-medium text-white/80">{{ $identity['fields']['name']['label'] }}</span>
                        <input type="text" name="nome" id="nome" placeholder="{{ $identity['fields']['name']['placeholder'] }}" required class="mt-2 w-full rounded-xl bg-white/10 border border-white/15 px-4 py-3 text-white placeholder-white/40 focus:border-[#6449a2] focus:outline-none focus:ring-2 focus:ring-[#6449a2]/40">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-white/80">{{ $identity['fields']['doctrine']['label'] }}</span>
                        <select name="base_doutrinaria" id="base_doutrinaria" required class="mt-2 w-full rounded-xl bg-white/10 border border-white/15 px-4 py-3 text-white focus:border-[#6449a2] focus:outline-none focus:ring-2 focus:ring-[#6449a2]/40">
                            <option value="">{{ $identity['fields']['doctrine']['placeholder'] }}</option>
                            @foreach ($bases_doutrinarias as $base)
                                <option value="{{ $base->id }}">{{ $base->nome }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <h2 class="text-xl font-semibold">{{ $ministries['title'] }}</h2>
                    <p class="text-white/60 text-sm mt-2">{{ $ministries['subtitle'] }}</p>
                </div>
                <div class="space-y-4">
                    <label class="block">
                        <span class="text-sm font-medium text-white/80">{{ $ministries['fields']['add'] }}</span>
                        <input type="text" id="ministerio_input" placeholder="{{ $ministries['fields']['placeholder'] }}" class="mt-2 w-full rounded-xl bg-white/10 border border-white/15 px-4 py-3 text-white placeholder-white/40 focus:border-[#6449a2] focus:outline-none focus:ring-2 focus:ring-[#6449a2]/40">
                    </label>
                    <input type="hidden" name="ministerios_eclesiasticos" id="ministerios_eclesiasticos">
                    <div class="min-h-[3rem] rounded-xl border border-dashed border-white/20 bg-white/5 p-3">
                        <div id="tags_container" class="flex flex-wrap gap-2 text-sm"></div>
                        <p class="text-xs text-white/40 mt-2">{{ $ministries['fields']['helper'] }}</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs text-white/50">{{ __('denominations.consent') }}</p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('congregacoes.cadastro') }}" class="inline-flex items-center justify-center px-5 py-3 rounded-xl border border-white/15 text-sm font-medium text-white/80 hover:border-white/40">
                        {{ __('denominations.header.to_congregations') }}
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-[#6449a2] hover:bg-[#584091] text-sm font-semibold shadow-lg shadow-[#6449a2]/30 transition">
                        {{ __('denominations.buttons.submit') }}
                    </button>
                </div>
            </div>
        </form>
    </main>
</div>
@endsection

@push('scripts')
<script>
    const input = document.getElementById('ministerio_input');
    const container = document.getElementById('tags_container');
    const hidden = document.getElementById('ministerios_eclesiasticos');
    const tags = [];

    input?.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && input.value.trim() !== '') {
            e.preventDefault();
            tags.push(input.value.trim());
            renderTags();
        }
    });

    function renderTags() {
        if (!container || !hidden) {
            return;
        }

        container.innerHTML = '';
        hidden.value = JSON.stringify(tags);
        tags.forEach((tag, idx) => {
            const chip = document.createElement('button');
            chip.type = 'button';
            chip.className = 'px-3 py-1 rounded-full bg-[#6449a2] hover:bg-[#584091] text-xs font-medium text-white transition';
            chip.textContent = tag;
            chip.addEventListener('click', () => {
                tags.splice(idx, 1);
                renderTags();
            });
            container.appendChild(chip);
        });
    }
</script>
@endpush
