@extends('layouts.main')

@section('title', 'Geral - AD Jerusalém')

@section('content')
    <div class="container">
        <h1>Controle Geral</h1>
        
        <div class="info">
            <h3>Dados Gerais</h3>
            <div class="card-container">
                <div class="info_item center">
                    <b>Data de Hoje: </b>
                    <p>{{date('d/m/Y')}}</p>
                    <p>@php
                            $diasDaSemana = [
                                0 => 'Domingo',
                                1 => 'Segunda-feira',
                                2 => 'Terça-feira',
                                3 => 'Quarta-feira',
                                4 => 'Quinta-feira',
                                5 => 'Sexta-feira',
                                6 => 'Sábado',
                            ];

                            $diaDaSemanaNumero = date('w');
                            echo $diasDaSemana[$diaDaSemanaNumero];
                        @endphp
                    </p>
                </div>
                @if ($culto_hoje)
                <div class="info_item center">
                    <b>Preletor: </b>
                    @if($culto_hoje[0]->preletor)
                        <p>{{$culto_hoje[0]->preletor}}</p>
                        <p>
                        @if ($culto_hoje[0]->evento_id)
                            {{$culto_hoje[0]->evento->titulo}} 
                        @else
                            Nenhum
                        @endif    
                        </p>                         
                    @else
                        <p>Não informado</p>
                        <p>
                            @if ($culto_hoje[0]->evento_id)
                                {{$culto_hoje[0]->evento->titulo}} 
                            @else
                                Nenhum
                            @endif    
                            </p>  
                    @endif
                </div>
                @else
                    <div class="info_item center">
                        <b>Nenhum culto cadastrado. </b>
                        <p><a href="/cultos/cultos">Registrar culto</a></p>
                    </div>
                @endif
            </div>
        </div>
        <div class="info" id="recados">
            <h3>Recados</h3>
            <div class="card-container">
                @if($recados)
                    @foreach ($recados as $item)
                    <div class="card info_item center">
                        <form action="{{ route('recados.excluir', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn-confirm"><i class="bi bi-check-circle"></i></button>
                        </form>
                        <p><i class="bi bi-exclamation-triangle"></i> {{$item->mensagem}}</p>  
                    </div>
                    @endforeach
                @else
                    <div class="card">
                        <p><i class="bi bi-exclamation-triangle"></i> Não há novos recados.</p>  
                    </div>
                @endif
            </div>    
        </div>
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
                        <div class="card-owner">{{$item->grupo->nome}}</div>
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
                @if ($membros)
                    @foreach ($membros as $item)
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
@endsection

