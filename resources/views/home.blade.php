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
    $diaDaSemanaNumero = (int) date('w');
    $diasDaSemana = [
        0 => 'Domingo',
        1 => 'Segunda-feira',
        2 => 'Terça-feira',
        3 => 'Quarta-feira',
        4 => 'Quinta-feira',
        5 => 'Sexta-feira',
        6 => 'Sábado',
    ];

    $groupsChartData = ($gruposDestaque ?? collect())->map(fn ($grupo) => [
        'label' => $grupo->nome,
        'value' => (int) ($grupo->integrantes_count ?? 0),
    ]);
@endphp

    <div class="container">
        <h1>Controle Geral</h1>
        
        <div class="info" id="dados-gerais">
            <h3>Dados Gerais</h3>
            <div class="dashboard-grid">
                <div class="dashboard-cards">
                    <div class="dashboard-card neutral">
                        <span class="label">Hoje</span>
                        <strong>{{ now()->format('d/m/Y') }}</strong>
                        <small>{{ $diasDaSemana[$diaDaSemanaNumero] ?? '' }}</small>
                    </div>
                    <div class="dashboard-card neutral">
                        <span class="label">Culto do dia</span>
                        @if ($culto_hoje && isset($culto_hoje[0]))
                            <strong>{{ $culto_hoje[0]->preletor ?? 'Preletor não informado' }}</strong>
                            <small>
                                @if ($culto_hoje[0]->evento_id)
                                    {{ $culto_hoje[0]->evento->titulo }}
                                @else
                                    Culto sem evento associado
                                @endif
                            </small>
                        @else
                            <strong>Sem culto registrado</strong>
                            <small><span class="link-standard" onclick="abrirJanelaModal('{{ route('cultos.form_criar') }}')">Agendar agora</span></small>
                        @endif
                    </div>
                    <div class="dashboard-card">
                        <span class="label">Membros ativos</span>
                        <strong>{{ number_format($dashboardStats['membros_ativos'] ?? 0, 0, ',', '.') }}</strong>
                        <small>Total registrado: {{ number_format($dashboardStats['membros_total'] ?? 0, 0, ',', '.') }}</small>
                    </div>
                    <div class="dashboard-card">
                        <span class="label">Novos no mês</span>
                        <strong>{{ number_format($dashboardStats['membros_novos'] ?? 0, 0, ',', '.') }}</strong>
                        <small>Entrada em {{ now()->locale('pt_BR')->translatedFormat('MMM/Y') }}</small>
                    </div>
                    <div class="dashboard-card">
                        <span class="label">Membros em grupos</span>
                        <strong>{{ number_format($dashboardStats['membros_em_grupos'] ?? 0, 0, ',', '.') }}</strong>
                        <small>{{ number_format($dashboardStats['membros_sem_grupo'] ?? 0, 0, ',', '.') }} sem vínculo</small>
                    </div>
                    <div class="dashboard-card">
                        <span class="label">Visitantes</span>
                        <strong>{{ number_format($dashboardStats['visitantes_mes'] ?? 0, 0, ',', '.') }}</strong>
                        <small>{{ number_format($dashboardStats['visitantes_total'] ?? 0, 0, ',', '.') }} desde o início</small>
                    </div>
                    <div class="dashboard-card">
                        <span class="label">Estrutura</span>
                        <strong>{{ number_format(($dashboardStats['grupos_total'] ?? 0) + ($dashboardStats['celulas_total'] ?? 0), 0, ',', '.') }}</strong>
                        <small>{{ $dashboardStats['grupos_total'] ?? 0 }} grupos · {{ $dashboardStats['celulas_total'] ?? 0 }} células</small>
                    </div>
                    <div class="dashboard-card">
                        <span class="label">Organização interna</span>
                        <strong>{{ number_format(($dashboardStats['departamentos_total'] ?? 0) + ($dashboardStats['setores_total'] ?? 0), 0, ',', '.') }}</strong>
                        <small>{{ $dashboardStats['departamentos_total'] ?? 0 }} departamentos · {{ $dashboardStats['setores_total'] ?? 0 }} setores</small>
                    </div>
                </div>
                <div class="dashboard-side">
                    <h4 style="margin-bottom: 12px;">Membros por grupos</h4>
                    <div id="groupsDashboardChart" data-chart='@json($groupsChartData)'></div>
                    <div style="margin-top: 16px; font-size: 0.85rem; color: rgba(255, 255, 255, 0.75);">
                        Próximos cultos: {{ number_format($dashboardStats['cultos_proximos'] ?? 0, 0, ',', '.') }} · Eventos agendados: {{ number_format($dashboardStats['eventos_proximos'] ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
        @if(module_enabled('recados'))
            @php
                $recadoDeleteRoute = Route::has('recados.excluir');
            @endphp
            <div class="info" id="recados">
                <h3>Recados</h3>
                <div class="card-container">
                    @if($recados)
                        @foreach ($recados as $item)
                        <div class="card card-recado info_item center" style="max-width: 50vw;">
                            @if($recadoDeleteRoute)
                            <form action="{{ route('recados.excluir', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn-confirm"><i class="bi bi-check-circle"></i></button>
                            </form>
                            @endif
                            <p><i class="bi bi-exclamation-triangle"></i> {{$item->mensagem}}</p>  
                            @if($item->membro)
                                <small class="hint right">Enviado por {{ $item->membro->nome }}</small>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <div class="card">
                            <p><i class="bi bi-exclamation-triangle"></i> Não há novos recados.</p>  
                        </div>
                    @endif
                </div>    
            </div>
        @endif
        <div class="info">
            <h3>Eventos</h3>
            <div class="card-container">
                @if ($eventos)
                    @foreach ($eventos as $item)
                    <div class="card">
                        <div class="card-date"><i class="bi bi-calendar-event"></i>
                            @php
                                $data = new DateTime($item->data_inicio);
                            @endphp
                            {{$data->format('d/m')}}
                        </div>
                        <div class="card-title">{{$item->titulo}}</div>
                        <div class="card-owner">{{optional($item->grupo)->nome ?? 'Geral'}}</div>
                        <div class="card-description">{{$item->descricao}}</div>
                    </div>
                    @endforeach
                @else
                    <div class="card">
                        <p><i class="bi bi-exclamation-triangle"></i> Não há eventos previstos para os próximos dias.</p>  
                    </div>
                @endif
                
            </div>
        </div>
        <div class="info">
            <h3>Aniversariantes</h3>
            <div class="card-container">
                @if ($aniversariantes)
                    @foreach ($aniversariantes as $item)
                    <div class="card">
                        <div class="card-date"><i class="bi bi-cake2"></i> 
                        @php
                            $data = new DateTime($item->data_nascimento);
                        @endphp
                        {{ $data->format("d/m")}}
                        </div>
                        <div class="card-title">{{$item->nome}}</div>
                        <div class="card-owner">
                            @if ($item->ministerio)
                                {{$item->ministerio->titulo}}
                            @else Não separado @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="card">
                        <p><i class="bi bi-cake2"></i> Não há aniversariantes esse mês.</p>
                    </div>
                @endif
            </div>            
        </div>
        <div class="info">
            <h3>Visitantes</h3>
            <div class="card-container">
                @if ($visitantes)
                    @foreach ($visitantes as $visitante)
                    <div class="info_item">
                        <div class="card-title"><i class="bi bi-person-raised-hand"></i> {{$visitante->nome}}</div>
                        <div class="card-owner">{{$visitante->sit_visitante->titulo}}</div>
                        <div class="card-description">{{$visitante->observacoes}}</div>
                    </div>
                    @endforeach
                @else
                    <div class="card">
                        <p><i class="bi bi-exclamation-triangle"></i> Ainda não recebemos visitantes.</p>  
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
            console.warn('Não foi possível processar os dados do gráfico de grupos.', error);
        }

        if (!Array.isArray(data) || data.length === 0) {
            chartEl.innerHTML = '<p class="hint">Ainda não há membros vinculados aos grupos.</p>';
            return;
        }

        const formatter = new Intl.NumberFormat('pt-BR');
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
