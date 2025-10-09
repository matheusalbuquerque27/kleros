@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')
@push('styles')
<style>
    #dados-gerais .dashboard-grid {
        display: grid;
        gap: 20px;
        margin-bottom: 25px;
    }

    #dados-gerais .dashboard-cards {
        display: grid;
        gap: 15px;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }

    #dados-gerais .dashboard-card {
        position: relative;
        border-radius: 18px;
        padding: 18px 20px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.16), rgba(255, 255, 255, 0)) var(--primary-color);
        color: var(--primary-contrast);
        border: 1px solid rgba(255, 255, 255, 0.15);
        box-shadow: 0 14px 28px rgba(17, 24, 39, 0.18);
    }

    #dados-gerais .dashboard-card.neutral {
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-color);
        border-color: rgba(255, 255, 255, 0.12);
    }

    #dados-gerais .dashboard-card span.label {
        display: block;
        font-size: 0.75rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        opacity: 0.7;
        margin-bottom: 6px;
    }

    #dados-gerais .dashboard-card strong {
        display: block;
        font-size: 2rem;
        font-weight: 600;
        line-height: 1.2;
    }

    #dados-gerais .dashboard-card small {
        display: block;
        font-size: 0.85rem;
        margin-top: 8px;
        opacity: 0.8;
    }

    #dados-gerais .dashboard-side {
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 22px 20px;
        box-shadow: 0 12px 24px rgba(17, 24, 39, 0.2);
    }

    #dados-gerais .chart-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
        padding: 10px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    #dados-gerais .chart-item:last-child {
        border-bottom: none;
    }

    #dados-gerais .chart-item header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.85);
    }

    #dados-gerais .chart-item header span.value {
        font-weight: 600;
        color: var(--secondary-color);
    }

    #dados-gerais .chart-bar {
        position: relative;
        height: 10px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.18);
        overflow: hidden;
    }

    #dados-gerais .chart-bar span {
        position: absolute;
        inset: 0;
        border-radius: inherit;
        background: linear-gradient(90deg, var(--secondary-color), var(--terciary-color));
    }

    @media (max-width: 960px) {
        #dados-gerais .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@php
    use Illuminate\Support\Carbon;

    $dashboard = trans('dashboard');
    $cards = $dashboard['general']['cards'];
    $numbers = $dashboard['numbers'];
    $days = $dashboard['days'];
    $intlLocale = $dashboard['intl_locale'] ?? str_replace('_', '-', app()->getLocale());
    $carbonLocale = str_replace('-', '_', app()->getLocale());
    Carbon::setLocale($carbonLocale);

    $formatNumber = function ($value) use ($numbers) {
        return number_format((int) ($value ?? 0), 0, $numbers['decimal'], $numbers['thousand']);
    };

    $dayName = $days[Carbon::now()->dayOfWeek] ?? '';
    $periodFormat = $cards['new_month']['period_format'] ?? 'MMM/Y';
    $periodLabel = Carbon::now()->translatedFormat($periodFormat);

    $groupsChartData = ($gruposDestaque ?? collect())->map(fn ($grupo) => [
        'label' => $grupo->nome,
        'value' => (int) ($grupo->integrantes_count ?? 0),
    ]);
@endphp

<div class="container">
    <h1>{{ $dashboard['title'] }}</h1>

    <div class="info" id="dados-gerais">
        <h3>{{ $dashboard['general']['heading'] }}</h3>
        <div class="dashboard-grid">
            <div class="dashboard-cards">
                <div class="dashboard-card neutral">
                    <span class="label">{{ $cards['today']['label'] }}</span>
                    <strong>{{ Carbon::now()->format('d/m/Y') }}</strong>
                    <small>{{ $dayName }}</small>
                </div>
                <div class="dashboard-card neutral">
                    <span class="label">{{ $cards['service']['label'] }}</span>
                    @if ($culto_hoje && isset($culto_hoje[0]))
                        <strong>{{ $culto_hoje[0]->preletor ?? $cards['service']['unknown_preacher'] }}</strong>
                        <small>
                            @if ($culto_hoje[0]->evento_id)
                                {{ $culto_hoje[0]->evento->titulo }}
                            @else
                                {{ $cards['service']['no_event'] }}
                            @endif
                        </small>
                    @else
                        <strong>{{ $cards['service']['no_service'] }}</strong>
                        <small>
                            <span class="link-standard" onclick="abrirJanelaModal('{{ route('cultos.form_criar') }}')">
                                {{ $cards['service']['cta'] }}
                            </span>
                        </small>
                    @endif
                </div>
                <div class="dashboard-card">
                    <span class="label">{{ $cards['members_active']['label'] }}</span>
                    <strong>{{ $formatNumber($dashboardStats['membros_ativos'] ?? 0) }}</strong>
                    <small>{{ __('dashboard.general.cards.members_active.small', ['total' => $formatNumber($dashboardStats['membros_total'] ?? 0)]) }}</small>
                </div>
                <div class="dashboard-card">
                    <span class="label">{{ $cards['new_month']['label'] }}</span>
                    <strong>{{ $formatNumber($dashboardStats['membros_novos'] ?? 0) }}</strong>
                    <small>{{ __('dashboard.general.cards.new_month.small', ['period' => $periodLabel]) }}</small>
                </div>
                <div class="dashboard-card">
                    <span class="label">{{ $cards['members_groups']['label'] }}</span>
                    <strong>{{ $formatNumber($dashboardStats['membros_em_grupos'] ?? 0) }}</strong>
                    <small>{{ __('dashboard.general.cards.members_groups.small', ['count' => $formatNumber($dashboardStats['membros_sem_grupo'] ?? 0)]) }}</small>
                </div>
                <div class="dashboard-card">
                    <span class="label">{{ $cards['visitors']['label'] }}</span>
                    <strong>{{ $formatNumber($dashboardStats['visitantes_mes'] ?? 0) }}</strong>
                    <small>{{ __('dashboard.general.cards.visitors.small', ['count' => $formatNumber($dashboardStats['visitantes_total'] ?? 0)]) }}</small>
                </div>
                <div class="dashboard-card">
                    <span class="label">{{ $cards['structure']['label'] }}</span>
                    @php
                        $structureTotal = ($dashboardStats['grupos_total'] ?? 0) + ($dashboardStats['celulas_total'] ?? 0);
                    @endphp
                    <strong>{{ $formatNumber($structureTotal) }}</strong>
                    <small>{{ __('dashboard.general.cards.structure.small', [
                        'groups' => $formatNumber($dashboardStats['grupos_total'] ?? 0),
                        'cells' => $formatNumber($dashboardStats['celulas_total'] ?? 0),
                    ]) }}</small>
                </div>
                <div class="dashboard-card">
                    <span class="label">{{ $cards['organization']['label'] }}</span>
                    @php
                        $organizationTotal = ($dashboardStats['departamentos_total'] ?? 0) + ($dashboardStats['setores_total'] ?? 0);
                    @endphp
                    <strong>{{ $formatNumber($organizationTotal) }}</strong>
                    <small>{{ __('dashboard.general.cards.organization.small', [
                        'departments' => $formatNumber($dashboardStats['departamentos_total'] ?? 0),
                        'sectors' => $formatNumber($dashboardStats['setores_total'] ?? 0),
                    ]) }}</small>
                </div>
            </div>
            <div class="dashboard-side">
                <h4 style="margin-bottom: 12px;">{{ $dashboard['chart']['title'] }}</h4>
                <div id="groupsDashboardChart" data-chart='@json($groupsChartData)'></div>
                <div style="margin-top: 16px; font-size: 0.85rem; color: rgba(255, 255, 255, 0.75);">
                    {{ __('dashboard.chart.subtitle', [
                        'services' => $formatNumber($dashboardStats['cultos_proximos'] ?? 0),
                        'events' => $formatNumber($dashboardStats['eventos_proximos'] ?? 0),
                    ]) }}
                </div>
            </div>
        </div>
    </div>

    @if (module_enabled('recados'))
        @php
            $recadoDeleteRoute = Route::has('recados.excluir');
        @endphp
        <div class="info" id="recados">
            <h3>{{ $dashboard['recados']['heading'] }}</h3>
            <div class="card-container">
                @if ($recados && $recados->count())
                    @foreach ($recados as $item)
                        <div class="card card-recado info_item center" style="max-width: 50vw;">
                            @if ($recadoDeleteRoute)
                                <form action="{{ route('recados.excluir', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-confirm"><i class="bi bi-check-circle"></i></button>
                                </form>
                            @endif
                            <p><i class="bi bi-exclamation-triangle"></i> {{ $item->mensagem }}</p>
                            @if ($item->membro)
                                <small class="hint right">{{ __('dashboard.recados.sent_by', ['name' => $item->membro->nome]) }}</small>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="card">
                        <p><i class="bi bi-exclamation-triangle"></i> {{ $dashboard['recados']['empty'] }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="info">
        <h3>{{ $dashboard['events']['heading'] }}</h3>
        <div class="card-container">
            @if ($eventos && $eventos->count())
                @foreach ($eventos as $item)
                    @php $eventDate = new DateTime($item->data_inicio); @endphp
                    <div class="card">
                        <div class="card-date"><i class="bi bi-calendar-event"></i> {{ $eventDate->format('d/m') }}</div>
                        <div class="card-title">{{ $item->titulo }}</div>
                        <div class="card-owner">{{ optional($item->grupo)->nome ?? $dashboard['events']['general_owner'] }}</div>
                        <div class="card-description">{{ $item->descricao ?? '' }}</div>
                    </div>
                @endforeach
            @else
                <div class="card">
                    <p><i class="bi bi-exclamation-triangle"></i> {{ $dashboard['events']['empty'] }}</p>
                </div>
            @endif
        </div>
    </div>

    <div class="info">
        <h3>{{ $dashboard['birthdays']['heading'] }}</h3>
        <div class="card-container">
            @if ($aniversariantes && $aniversariantes->count())
                @foreach ($aniversariantes as $item)
                    @php $birthDate = new DateTime($item->data_nascimento); @endphp
                    <div class="card">
                        <div class="card-date"><i class="bi bi-cake2"></i> {{ $birthDate->format('d/m') }}</div>
                        <div class="card-title">{{ $item->nome }}</div>
                        <div class="card-owner">
                            @if ($item->ministerio)
                                {{ $item->ministerio->titulo }}
                            @else
                                {{ $dashboard['birthdays']['no_ministry'] }}
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card">
                    <p><i class="bi bi-cake2"></i> {{ $dashboard['birthdays']['empty'] }}</p>
                </div>
            @endif
        </div>
    </div>

    <div class="info">
        <h3>{{ $dashboard['visitors']['heading'] }}</h3>
        <div class="card-container">
            @if ($visitantes && $visitantes->count())
                @foreach ($visitantes as $visitante)
                    <div class="info_item">
                        <div class="card-title"><i class="bi bi-person-raised-hand"></i> {{ $visitante->nome }}</div>
                        <div class="card-owner">{{ optional($visitante->sit_visitante)->titulo }}</div>
                        <div class="card-description">{{ $visitante->observacoes }}</div>
                    </div>
                @endforeach
            @else
                <div class="card">
                    <p><i class="bi bi-exclamation-triangle"></i> {{ $dashboard['visitors']['empty'] }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartEl = document.getElementById('groupsDashboardChart');
        if (!chartEl) {
            return;
        }

        let data = [];
        try {
            data = JSON.parse(chartEl.dataset.chart || '[]');
        } catch (error) {
            console.warn('Chart data parse error.', error);
        }

        if (!Array.isArray(data) || data.length === 0) {
            chartEl.innerHTML = `<p class="hint">{{ $dashboard['chart']['empty'] }}</p>`;
            return;
        }

        const formatter = new Intl.NumberFormat(@json($intlLocale));
        const maxValue = data.reduce((max, item) => Math.max(max, Number(item.value) || 0), 0) || 1;

        chartEl.innerHTML = data.map((item) => {
            const absolute = Number(item.value) || 0;
            const width = Math.max(8, Math.round((absolute / maxValue) * 100));
            return `
                <div class="chart-item">
                    <header>
                        <span>${item.label}</span>
                        <span class="value">${formatter.format(absolute)}</span>
                    </header>
                    <div class="chart-bar"><span style="width: ${width}%;"></span></div>
                </div>
            `;
        }).join('');
    });
</script>
@endpush
@endsection
