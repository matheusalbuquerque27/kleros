@extends('layouts.site')

@section('title', 'Cadastre sua denominação — Kleros')

@section('content')
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
        <div class="max-w-5xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="{{ route('site.home') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/kleros-logo.svg') }}" alt="Kleros" class="h-8 w-auto">
                <div class="leading-tight">
                    <span class="font-semibold text-lg">Kleros</span>
                    <span class="block text-xs text-white/60">Ecossistema para Igrejas</span>
                </div>
            </a>
            <a href="{{ route('congregacoes.cadastro') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-white/20 hover:border-white/40 text-sm">
                Já tem a denominação? Cadastre a congregação
            </a>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-16">
        <div class="text-center md:text-left">
            <span class="uppercase tracking-[0.2em] text-xs text-white/50">Check-in denominacional</span>
            <h1 class="mt-3 text-3xl md:text-4xl font-semibold">Cadastre sua denominação</h1>
            <p class="mt-4 text-white/70 text-base md:text-lg">Informe os dados principais para que possamos organizar suas igrejas e habilitar os recursos do Kleros para toda a rede.</p>
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
                    <h2 class="text-xl font-semibold">Identidade denominacional</h2>
                    <p class="text-white/60 text-sm mt-2">Esses dados aparecerão para todas as congregações vinculadas.</p>
                </div>
                <div class="grid md:grid-cols-1">
                    <label class="block">
                        <span class="text-sm font-medium text-white/80">Nome completo</span>
                        <input type="text" name="nome" id="nome" placeholder="Nome oficial da denominação" required class="mt-2 w-full rounded-xl bg-white/10 border border-white/15 px-4 py-3 text-white placeholder-white/40 focus:border-[#6449a2] focus:outline-none focus:ring-2 focus:ring-[#6449a2]/40">
                    </label>
                    <label class="block md:col-span-2">
                        <span class="text-sm font-medium text-white/80">Base doutrinária</span>
                        <select name="base_doutrinaria" id="base_doutrinaria" required class="mt-2 w-full rounded-xl bg-white/10 border border-white/15 px-4 py-3 text-white focus:border-[#6449a2] focus:outline-none focus:ring-2 focus:ring-[#6449a2]/40">
                            <option value="">Selecione a tradição/confissão</option>
                            @foreach ($bases_doutrinarias as $base)
                                <option value="{{ $base->id }}">{{ $base->nome }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <h2 class="text-xl font-semibold">Estrutura ministerial</h2>
                    <p class="text-white/60 text-sm mt-2">Liste os ministérios ou cargos utilizados (ex.: Pastor, Presbítero, Diácono). Eles serão sugeridos ao cadastrar congregações.</p>
                </div>
                <div class="space-y-4">
                    <label class="block">
                        <span class="text-sm font-medium text-white/80">Adicione os ministérios e pressione ENTER</span>
                        <input type="text" id="ministerio_input" placeholder="Digite o ministério e pressione ENTER" class="mt-2 w-full rounded-xl bg-white/10 border border-white/15 px-4 py-3 text-white placeholder-white/40 focus:border-[#6449a2] focus:outline-none focus:ring-2 focus:ring-[#6449a2]/40">
                    </label>
                    <input type="hidden" name="ministerios_eclesiasticos" id="ministerios_eclesiasticos">
                    <div class="min-h-[3rem] rounded-xl border border-dashed border-white/20 bg-white/5 p-3">
                        <div id="tags_container" class="flex flex-wrap gap-2 text-sm"></div>
                        <p class="text-xs text-white/40 mt-2">Clique em um item para removê-lo.</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs text-white/50">Ao continuar você autoriza a equipe Kleros a entrar em contato para validar as informações.</p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('congregacoes.cadastro') }}" class="inline-flex items-center justify-center px-5 py-3 rounded-xl border border-white/15 text-sm font-medium text-white/80 hover:border-white/40">Ir para cadastro de congregações</a>
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-[#6449a2] hover:bg-[#584091] text-sm font-semibold shadow-lg shadow-[#6449a2]/30 transition">Salvar e continuar</button>
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

    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && input.value.trim() !== '') {
            e.preventDefault();
            tags.push(input.value.trim());
            renderTags();
        }
    });

    function renderTags() {
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
