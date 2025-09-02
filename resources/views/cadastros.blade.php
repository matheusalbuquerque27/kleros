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
        <a href="{{route('cultos.create')}}"><button class="btn"><i class="bi bi-plus-circle"></i> Agendar culto</button></a>
        <a href="/cultos/agenda"><button class="btn"><i class="bi bi-arrow-right-circle"></i> Próximos cultos</button></a>
        <a href="{{route('cultos.complete', 'adicionar')}}"><button class="btn"><i class="bi bi-plus-circle-fill"></i> Registrar</button></a>
        <a href="/cultos/historico"><button class="btn"><i class="bi bi-card-list"></i> Histórico</button></a>
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
        <a href="{{route('reunioes.create')}}"><button class="btn mg-top-10"><i class="bi bi-plus-circle"></i> Nova reunião</button></a>
        <a href="/eventos/historico"><button class="btn mg-top-10"><i class="bi bi-card-list"></i> Histórico</button></a>
        <a href="/eventos/agenda"><button class="btn mg-top-10"><i class="bi bi-arrow-right-circle"></i> Próximos reuniões</button></a>
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
                            <form method="POST">
                                <a href="/grupos/integrantes/{{$item->id}}"><button type="button" class="btn-options"><i class="bi bi-eye"></i> Membros</button></a>
                                <button type="button" class="btn-options" onclick="abrirJanelaModal('{{route('grupos.form_editar', $item->id)}}')"><i class="bi bi-pencil-square"></i> Editar</button>
                                @csrf
                                @method('DELETE')
                                <button type="button" class="delete-grupo btn-options" data-action="/grupos/" id="{{$item->id}}"><i class="bi bi-trash"></i> Excluir</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card">
                    <p><i class="bi bi-exclamation-triangle"></i> Nenhum membro foi incluído até o momento.</p>  
                </div>
            @endif
        </div>
        <a href="/grupos/adicionar"><button class="btn mg-top-10"><i class="bi bi-plus-circle"></i> Novo grupo</button></a>
        <button id="grupos" class="imprimir btn mg-top-10" data-action="0"><i class="bi bi-printer"></i> Imprimir lista</button>
    </div>
    <div class="info" id="ministerios">
        <h3>Ministérios</h3>
        <div class="card-container">
            @if(count($ministerios) > 0)
                @foreach ($ministerios as $item)
                    <div class="list-item">
                        <div class="item-15">
                            <div class="card-title">{{$item->titulo}}</div>
                        </div>
                        <div class="item-2"></div>
                        <div class="item-15">
                            <form action="POST">
                                @csrf
                                @method('DELETE')
                                <a href="/ministerios/lista/{{$item->id}}"><button type="button" class="btn-options"><i class="bi bi-eye"></i> Membros</button></a>
                                <a href="/ministerios/lista/{{$item->id}}"><button type="button" class="btn-options"><i class="bi bi-pencil-square"></i> Editar</button></a>
                                <button class="delete-ministerio btn-options" data-action="/ministerios/" id="{{$item->id}}"><i class="bi bi-trash"></i> Excluir</button>
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
        <a href="/ministerios/adicionar"><button class="btn mg-top-10"><i class="bi bi-plus-circle"></i> Novo ministério</button></a>
        <button id="ministerios" class="imprimir btn mg-top-10" data-action="0"><i class="bi bi-printer"></i> Imprimir lista</button>
    </div>

    <div class="info" id="departamentos">
        <h3>Departamentos</h3>
        <a href="/departamentos/adicionar"><button class="btn mg-top-10"><i class="bi bi-plus-circle"></i> Novo departamento</button></a>
        <button id="departamentos" class="imprimir btn mg-top-10" data-action="0"><i class="bi bi-printer"></i> Imprimir lista</button>
    </div>

    <div class="info" id="cursos">
        <h3>Cursos</h3>
        <div class="card-container">
            @if(count($cursos) > 0)
                @foreach ($cursos as $item)
                    <div class="alterlist">
                        <div class="item-15">
                            <div class="card-title"><img style="width: 2em; border-radius: 10px;" src="{{'storage/'. $item->icone}}" alt=""> {{$item->titulo}}</div>
                        </div>
                        <div class="item-2">
                            <div class="card-description">{{$item->descricao}}</div>
                        </div>
                        <div class="item-15">
                            <form method="POST">
                                <a href="/grupos/integrantes/{{$item->id}}"><button type="button" class="btn-options"><i class="bi bi-eye"></i> Ver</button></a>
                                @csrf
                                @method('DELETE')
                                <button type="button" class="delete-grupo btn-options" data-action="/grupos/" id="{{$item->id}}"><i class="bi bi-trash"></i> Excluir</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card">
                    <p><i class="bi bi-exclamation-triangle"></i> Nenhum membro foi incluído até o momento.</p>  
                </div>
            @endif
        </div>

        <a href="{{route('cursos.create')}}"><button class="btn mg-top-10"><i class="bi bi-plus-circle"></i> Novo curso</button></a>
        <button id="cursos" class="imprimir btn mg-top-10" data-action="0"><i class="bi bi-printer"></i> Imprimir lista</button>
        
    </div>

    <div class="info" id="celulas">
        <h3>GCA</h3>
        <a href="/celulas/adicionar"><button class="btn mg-top-10"><i class="bi bi-plus-circle"></i> Novo GCA</button></a>
        <button id="celulas" class="imprimir btn mg-top-10" data-action="0"><i class="bi bi-printer"></i> Imprimir relatório</button>
    </div>
@include('noticias.includes.destaques', ['destaques' => $destaques])
</div>{{--container--}}



@endsection

@push('scripts')
    <script>

        $(document).ready(function(){
            
            function popup(msg){

                $('#msg_content').text(msg)

            }

            async function popup_response(){

                return new Promise((resolve) => {
                    $('#confirmaBtn').click(function() {
                        resolve(true)
                    })
                    $('#cancelaBtn').click(function() {
                        resolve(false)
                    })
                })

            }
    
            $('.delete-grupo').click(async function(event) {
                const elemento = $(this);
                const token = $('meta[name="csrf-token"]').attr('content');
                const url = elemento.attr('data-action') + elemento.attr('id')
                event.preventDefault()

                popup('Você realmente deseja excluir este grupo?')
                $('.popup').addClass('mostrar');
                const response = await popup_response();
                
                if(response){

                    $.ajax({
                        url: url, // Rota de destino
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: elemento.attr('id')
                        },
                        success: function(response) {
                                if(response.success){
                                    setTimeout(function() {
                                    window.location.reload();
                                }, 200);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Erro na requisição:', textStatus, errorThrown);
                            alert('Ocorreu um erro: ' + textStatus + ' - ' + errorThrown);
                        }
                    })

                } else {
                    $('.popup').removeClass('mostrar');
                }

            });

            $(".delete-ministerio").click(async function(event){
                event.preventDefault();
                const elemento = $(this);       
                const token = $('meta[name="csrf-token"]').attr('content');
                const url = elemento.attr('data-action') + elemento.attr('id');

                popup('Você realmente deseja excluir este ministério?')
                $('.popup').addClass('mostrar');
                const response = await popup_response();

                if(response){

                    $.ajax({
                        url: url, // Rota de destino
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: elemento.attr('id')
                        },
                        success: function(response) {
                                if(response.success){
                                    setTimeout(function() {
                                    window.location.reload();
                                }, 200);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Erro na requisição:', textStatus, errorThrown);
                            alert('Ocorreu um erro: ' + textStatus + ' - ' + errorThrown);
                        }
                    })

                    } else {
                    $('.popup').removeClass('mostrar');
                }

            })

            
            $('.imprimir').click(function() {
                const printData = $(this).attr('data-action');
                const reference = $(this).attr('id')
                window.open(`/${reference}/imprimir/${printData}`, '_blank');
            });

        });
        
    </script>
@endpush

