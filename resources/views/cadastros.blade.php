@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')


<div class="container">
   
    <h1>Cadastros</h1>
    <div class="info" id="cultos">
        <h3>Cultos</h3>
        <b>Próximos cultos previstos: </b>
        <div class="card-container">
            @if (count($cultos) > 0)
                @foreach ($cultos as $item)
                <div class="info_item">
                    <div class="info-edit" onclick="abrirJanelaModal('{{route('cultos.form_editar', $item->id)}}')"><i class="bi bi-pencil-square"></i></div>
                    <p><i class="bi bi-calendar-event"></i>
                    @php
                        $data = new DateTime($item->data_culto);
                    @endphp
                    {{$data->format("d/m")}}
                    </p>
                    <p><i class="bi bi-mic"></i> Preletor: {{$item->preletor}}</p>
                    <p><b>Evento</b>: @if ($item->evento_id)
                        {{$item->evento->titulo}}
                    @else Nenhum @endif
                    </p>
                </div>
                @endforeach
            @else
                <div class="card">
                    <p><i class="bi bi-exclamation-triangle"></i> Não há cultos previstos para os próximos dias.</p>  
                </div>
            @endif
            
            
        </div>
        <button class="btn" onclick="abrirJanelaModal('{{route('cultos.form_criar')}}')"><i class="bi bi-plus-circle"></i> Agendar culto</button>
        <a href="/cultos/agenda"><button class="btn"><i class="bi bi-arrow-right-circle"></i> Próximos cultos</button></a>
        <a href="{{route('cultos.complete', 'adicionar')}}"><button class="btn"><i class="bi bi-plus-circle-fill"></i> Registrar</button></a>
        <a href="/cultos/historico"><button class="btn"><i class="bi bi-card-list"></i> Histórico</button></a>
    </div>
    
    <div class="info" id="escalas">
        <h3>Escalas</h3>
        <b>Tipos de escala cadastrados:</b>
        <div class="card-container">
            @if($tiposEscala->count())
                @foreach($tiposEscala as $tipo)
                    <div class="list-item">
                        <div class="item-2">
                            <div class="card-title"><i class="bi bi-diagram-3"></i> {{ $tipo->nome }}</div>
                            <div class="card-description hint">Status: {{ $tipo->ativo ? 'Ativo' : 'Inativo' }}</div>
                        </div>
                        <div class="item-15">
                            <button type="button" class="btn-options" onclick="window.location.href='{{ route('escalas.painel', ['tipo' => $tipo->id]) }}'"><i class="bi bi-list-task"></i> Ver escalas</button>
                            <button type="button" class="btn-options" onclick="abrirJanelaModal('{{ route('escalas.tipos.form_editar', $tipo->id) }}')"><i class="bi bi-pencil-square"></i> Editar</button>
                            <form id="delete-tipo-{{ $tipo->id }}" action="{{ route('escalas.tipos.destroy', $tipo->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-options danger" onclick="handleSubmit(event, this.form, 'Deseja realmente excluir este tipo de escala?')"><i class="bi bi-trash"></i> Excluir</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card">
                    <p><i class="bi bi-exclamation-triangle"></i> Nenhum tipo de escala foi cadastrado ainda.</p>
                </div>
            @endif
        </div>
        <button class="btn mg-top-10" onclick="abrirJanelaModal('{{ route('escalas.tipos.form_criar') }}')"><i class="bi bi-plus-circle"></i> Novo tipo de escala</button>
        <button class="btn mg-top-10" onclick="abrirJanelaModal('{{ route('escalas.form_criar') }}')"><i class="bi bi-plus-circle-fill"></i> Gerar escala</button>
        <button id="escalas" class="imprimir btn mg-top-10" data-action="0"><i class="bi bi-printer"></i> Imprimir</button>
    </div>

    <div class="info" id="eventos">
        <h3>Eventos</h3>
        <b>Próximos eventos previstos: </b>
        <div class="card-container">
            @if (count($eventos) > 0)
                    @foreach ($eventos as $item)
                    <div class="card">
                        <div class="card-edit" onclick="abrirJanelaModal('{{route('eventos.form_editar', $item->id)}}')"><i class="bi bi-pencil-square"></i></div>
                        <div class="card-date"><i class="bi bi-calendar-event"></i>
                            @php
                                $data = new DateTime($item->data_inicio);
                            @endphp
                            {{$data->format('d/m')}}
                        </div>
                        <div class="card-title">{{$item->titulo}}</div>
                        <div class="card-owner">{{optional($item->grupo)->nome ?? 'Geral'}}</div>
                        <div class="card-description">{{$item->descricao ?? 'Sem descrição'}}</div>
                    </div>
                    @endforeach
                @else
                    <div class="card">
                        <p><i class="bi bi-exclamation-triangle"></i> Não há eventos previstos para os próximos dias.</p>  
                    </div>
                @endif
        </div>
        <a href="/eventos/adicionar"><button class="btn mg-top-10"><i class="bi bi-plus-circle"></i> Novo evento</button></a>
        <a href="/eventos/historico"><button class="btn mg-top-10"><i class="bi bi-card-list"></i> Histórico de eventos</button></a>
        <a href="/eventos/agenda"><button class="btn mg-top-10"><i class="bi bi-arrow-right-circle"></i> Próximos eventos</button></a>
    </div>

    <div class="info" id="reunioes">
        <h3>Reuniões</h3>
        <b>Próximas reuniões previstas: </b>
        <div class="card-container">
            @if (count($reunioes) > 0)
                    @foreach ($reunioes as $item)
                    <div class="card">
                        <div class="card-edit" onclick="abrirJanelaModal('{{route('reunioes.form_editar', $item->id)}}')"><i class="bi bi-pencil-square"></i></div>
                        <div class="card-date"><i class="bi bi-calendar-event"></i>
                            @php
                                $data = new DateTime($item->data_inicio);
                            @endphp
                            {{$data->format('d/m')}} - {{$data->format('H:i')}} h
                        </div>
                        <div class="card-title">{{$item->assunto}}</div>
                        <div class="card-description">{{$item->descricao ?? 'Sem descrição'}}</div>
                    </div>
                    @endforeach
                @else
                    <div class="card">
                        <p><i class="bi bi-exclamation-triangle"></i> Não há reuniões previstas para os próximos dias.</p>  
                    </div>
                @endif
        </div>
        <button class="btn mg-top-10" onclick="abrirJanelaModal('{{route('reunioes.form_criar')}}')"><i class="bi bi-plus-circle"></i> Nova reunião</button></a>
        <a href="{{route('reunioes.painel')}}"><button class="btn mg-top-10"><i class="bi bi-card-list"></i> Histórico</button></a>
        <a href="/eventos/agenda"><button class="btn mg-top-10"><i class="bi bi-arrow-right-circle"></i> Próximos reuniões</button></a>
    </div>

    <div class="info" id="pesquisas">
        <h3>Pesquisas</h3>
        <b>Pesquisas abertas:</b>
        <div class="card-container">
            @if(count($pesquisas) > 0)
                @foreach($pesquisas as $item)
                    <div class="card">
                        <div class="card-edit" onclick="abrirJanelaModal('{{ route('pesquisas.form_editar', $item->id) }}')"><i class="bi bi-pencil-square"></i></div>
                        <div class="card-title">{{ $item->titulo }}</div>
                        <div class="card-date">
                            <i class="bi bi-calendar-event"></i>
                            @if($item->data_fim)
                                <span>Até {{ $item->data_fim?->format('d/m/Y') }}</span>
                            @endif
                        </div>
                        <div class="card-meta"><b>Editor: </b> {{ optional($item->criador)->nome ?? 'Responsável não informado' }}</div>
                    </div>
                @endforeach
            @else
                <div class="card">
                    <p><i class="bi bi-exclamation-triangle"></i> Nenhuma pesquisa aberta no momento.</p>
                </div>
            @endif
        </div>
        <button class="btn mg-top-10" onclick="abrirJanelaModal('{{ route('pesquisas.form_criar') }}')"><i class="bi bi-plus-circle"></i> Nova pesquisa</button>
        <a href="{{ route('pesquisas.painel') }}"><button class="btn mg-top-10"><i class="bi bi-card-list"></i> Painel de pesquisas</button></a>
    </div>

    <div class="info" id="visitantes">
        <h3>Visitantes</h3>
        <div class="card-container">
            @if ($visitantes_total)
                <div class="info_item">
                    <p>Visitas neste mês: </p>
                    <h2>{{$visitantes_total}}</h3>
                </div>
            @else
                <div class="card">
                    <p><i class="bi bi-exclamation-triangle"></i> Ainda não há histórico de visitantes.</p>  
                </div>
            @endif
        </div>
        <a href="/visitantes/adicionar"><button class="btn mg-top-10"><i class="bi bi-plus-circle"></i> Cadastrar visitante</button></a>
        <a href="/visitantes/historico"><button class="btn mg-top-10"><i class="bi bi-card-list"></i> Histórico de visitantes</button></a>
    </div>

    <div class="info" id="grupos">
        <h3>Grupos</h3>

        <div class="card-container">
            
            @if(count($grupos) > 0)
                @foreach ($grupos as $item)
                    <div class="list-item">
                        <div class="item-15">
                            <div class="card-title">{{$item->nome}}</div>
                            <div class="card-description">{{$item->descricao}}</div>
                        </div>
                        <div class="item-2">
                            <div class="card-description"><b>Liderança: </b>{{optional($item->lider)->nome}} @if($item->colider) | @endif {{optional($item->colider)->nome}}</div>
                        </div>
                        <div class="item-15">
                            <a href="/grupos/integrantes/{{$item->id}}"><button type="button" class="btn-options"><i class="bi bi-eye"></i> Membros</button></a>
                            <button type="button" class="btn-options" onclick="abrirJanelaModal('{{route('grupos.form_editar', $item->id)}}')"><i class="bi bi-pencil-square"></i> Editar</button>
                            <form id="delete-grupo-{{$item->id}}" action="{{ route('grupos.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-options danger" onclick="handleSubmit(event, this.form, 'Deseja realmente excluir este grupo?')"><i class="bi bi-trash"></i> Excluir</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card">
                    <p><i class="bi bi-exclamation-triangle"></i> Nenhum grupo foi criado até o momento.</p>  
                </div>
            @endif
        </div>
        <button class="btn mg-top-10" onclick="abrirJanelaModal('{{route('grupos.form_criar')}}')"><i class="bi bi-plus-circle"></i> Novo Grupo</button>
        <button id="grupos" class="imprimir btn mg-top-10" data-action="0"><i class="bi bi-printer"></i> Imprimir lista</button>
    </div>

    <div class="info" id="departamentos">
        <h3>Departamentos</h3>
        <div class="card-container">
            @if(count($departamentos) > 0)
                @foreach ($departamentos as $item)
                    <div class="list-item">
                        <div class="item-15">
                            <div class="card-title"><i class="bi bi-intersect"></i>{{$item->nome}}</div>
                            <div class="card-description">{{$item->descricao}}</div>
                        </div>
                        <div class="item-2">
                            <div class="card-description"><b>Liderança: </b>{{optional($item->lider)->nome}} @if($item->colider) | @endif {{optional($item->colider)->nome}}</div>
                        </div>
                        <div class="item-15">
                            <a href="/departamentos/integrantes/{{$item->id}}"><button type="button" class="btn-options"><i class="bi bi-eye"></i> Equipe</button></a>
                            <button type="button" class="btn-options" onclick="abrirJanelaModal('{{route('departamentos.form_editar', $item->id)}}')"><i class="bi bi-pencil-square"></i> Editar</button>
                            <form id="delete-departamento-{{$item->id}}" action="{{ route('departamentos.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-options danger" onclick="handleSubmit(event, this.form, 'Deseja realmente excluir este departamento?')"><i class="bi bi-trash"></i> Excluir</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card">
                    <p><i class="bi bi-exclamation-triangle"></i> Nenhum departamento foi adicionado até o momento.</p>  
                </div>
            @endif
        </div>
        <button class="btn mg-top-10" onclick="abrirJanelaModal('{{route('departamentos.form_criar')}}')"><i class="bi bi-plus-circle"></i> Novo departamento</button>
        <button class="imprimir btn mg-top-10" data-action="0"><i class="bi bi-printer"></i> Imprimir lista</button>
    </div>

    @if($congregacao->config->agrupamentos == 'setor')
    <div class="info" id="setores">
        <h3>Setores</h3>
        <div class="card-container">
            @if(($setores ?? collect())->count() > 0)
                @foreach ($setores as $setor)
                    <div class="list-item">
                        <div class="item-15">
                            <div class="card-title">{{ $setor->nome }}</div>
                            @if($setor->lider)
                                <div class="card-description"><b>Liderança: </b>{{ $setor->lider->nome }}@if($setor->colider) {{ ' / ' . $setor->colider->nome }}@endif</div>
                            @endif
                        </div>
                        <div class="item-2">
                            <div class="card-description">{{ $setor->descricao ?: 'Sem descrição cadastrada.' }}</div>
                            @php
                                $relacionados = $setor->departamentos->pluck('nome')
                                    ->merge($setor->grupos->pluck('nome'))
                                    ->filter()
                                    ->unique();
                            @endphp
                            <small class="hint"><b>Relacionados:</b> {{ $relacionados->implode(', ') ?: 'Nenhum agrupamento vinculado.' }}</small>
                        </div>
                        <div class="item-15">
                            <button type="button" class="btn-options" onclick="abrirJanelaModal('{{ route('setores.form_editar', $setor->id) }}')"><i class="bi bi-pencil-square"></i> Editar</button>
                            <form id="delete-setor-{{ $setor->id }}" action="{{ route('setores.destroy', $setor->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-options danger" onclick="handleSubmit(event, this.form, 'Deseja realmente excluir este setor?')"><i class="bi bi-trash"></i> Excluir</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card">
                    <p><i class="bi bi-exclamation-triangle"></i> Nenhum setor foi cadastrado até o momento.</p>
                </div>
            @endif
        </div>
        <button class="btn mg-top-10" onclick="abrirJanelaModal('{{ route('setores.form_criar') }}')"><i class="bi bi-plus-circle"></i> Novo Setor</button>
        <button class="imprimir btn mg-top-10" data-action="0"><i class="bi bi-printer"></i> Imprimir lista</button>
    </div>
    @endif

    <div class="info" id="ministerios">
        <h3>Ministérios</h3>
        <div class="card-container">
            @if(count($ministerios) > 0)
                @foreach ($ministerios as $item)
                    <div class="list-item">
                        <div class="item-15">
                            <div class="card-title">{{$item->titulo}}</div>
                        </div>
                        <div class="item-2">
                            <div class="card-description">{{$item->descricao}}</div>
                        </div>
                        <div class="item-15">
                            <a href="/ministerios/lista/{{$item->id}}"><button type="button" class="btn-options"><i class="bi bi-eye"></i> Membros</button></a>
                            <button type="button" onclick="abrirJanelaModal('{{route('ministerios.form_editar', $item->id)}}')" class="btn-options"><i class="bi bi-pencil-square"></i> Editar</button>
                            <form id="delete-ministerio-{{$item->id}}" action="{{ route('ministerios.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-options danger" onclick="handleSubmit(event, this.form, 'Deseja realmente excluir este ministério?')"><i class="bi bi-trash"></i> Excluir</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card">
                    <p><i class="bi bi-exclamation-triangle"></i> Nenhum membro foi designado a algum ministério.</p>  
                </div>
            @endif
        </div>
        <button class="btn mg-top-10" onclick="abrirJanelaModal('{{route('ministerios.form_criar')}}')"><i class="bi bi-plus-circle"></i> Novo ministério</button>
        <button id="ministerios" class="imprimir btn mg-top-10" data-action="0"><i class="bi bi-printer"></i> Imprimir lista</button>
    </div>

    <div class="info" id="cursos">
        <h3>Cursos</h3>
        <div class="card-container">
            @if(count($cursos) > 0)
                @foreach ($cursos as $item)
                    <div class="alterlist">
                        <div class="item-15">
                            <div class="card-title"><img style="width: 2em; border-radius: 10px;" src="{{'storage/'. ($item->icone ?? 'images/podcast.png')}}" alt=""> {{$item->titulo}}</div>
                        </div>
                        <div class="item-2">
                            <div class="card-description">{{$item->descricao}}</div>
                        </div>
                        <div class="item-15">
                            <a href="/grupos/integrantes/{{$item->id}}"><button type="button" class="btn-options"><i class="bi bi-eye"></i> Ver</button></a>
                            <form id="delete-curso-{{$item->id}}" action="{{ route('cursos.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-options danger" onclick="handleSubmit(event, this.form, 'Deseja realmente excluir este curso?')"><i class="bi bi-trash"></i> Excluir</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card">
                    <p><i class="bi bi-exclamation-triangle"></i> Nenhum curso foi registrado até o momento.</p>  
                </div>
            @endif
        </div>

        <button class="btn mg-top-10" onclick="abrirJanelaModal('{{route('cursos.form_criar')}}')"><i class="bi bi-plus-circle"></i> Novo curso</button>
        <button id="cursos" class="imprimir btn mg-top-10" data-action="0"><i class="bi bi-printer"></i> Imprimir lista</button>
        
    </div>

    @if(module_enabled('celulas'))
    <div class="info" id="celulas">
        <h3>Células</h3>

        <div class="card-container">
            @if(count($celulas) > 0)
                @foreach ($celulas as $item)
                    <div class="alterlist">
                        <div class="item-15">
                            <div class="card-title"><i class="bi bi-cup-hot"></i> {{$item->identificacao}}</div>
                            <div class="card-description"><b>Liderança: </b>{{optional($item->lider)->nome}} @if($item->colider) | @endif {{optional($item->colider)->nome}}</div>
                        </div>

                        <div class="item-2">
                            <div class="card-description">
                                <b>Encontro semanal: </b> {{ diaSemana($item->dia_encontro) }} / 
                                {{ date('H:i', strtotime($item->hora_encontro)) }} h
                            </div>
                        </div>
                        <div class="item-15">
                            <a href="/grupos/integrantes/{{$item->id}}"><button type="button" class="btn-options"><i class="bi bi-eye"></i> Ver</button></a>
                            <form id="delete-celula-{{$item->id}}" action="{{ route('grupos.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-options danger" onclick="handleSubmit(event, this.form, 'Deseja realmente excluir esta célula?')"><i class="bi bi-trash"></i> Excluir</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card">
                    <p><i class="bi bi-exclamation-triangle"></i> Nenhum cadastro foi efetuado até o momento.</p>  
                </div>
            @endif
        </div>

        @if(module_enabled('celulas') && Route::has('celulas.form_criar'))
            <button class="btn mg-top-10" onclick="abrirJanelaModal('{{ route('celulas.form_criar') }}')"><i class="bi bi-plus-circle"></i> Nova Célula</button>
        @endif
        <button id="celulas" class="imprimir btn mg-top-10" data-action="0"><i class="bi bi-printer"></i> Imprimir relatório</button>
    </div>
    @endif
@include('noticias.includes.destaques', ['destaques' => $destaques])
</div>{{--container--}}



@endsection

@push('scripts')
    <script>

        $(document).ready(function(){
            $('.imprimir').click(function() {
                const printData = $(this).attr('data-action');
                const reference = $(this).attr('id');
                window.open(`/${reference}/imprimir/${printData}`, '_blank');
            });
        });
        
    </script>
@endpush
