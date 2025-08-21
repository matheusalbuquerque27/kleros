@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')

<div class="container">
    <h1>Informações de Visitante</h1>
    
    <form action="{{ route('visitantes.destroy', $visitante->id) }}" method="post" onsubmit="return handleSubmit(event, this, 'Tem certeza que deseja excluir?')">
        @csrf
        <div class="data-view">
            <div class="section">
                <h3>Informações</h3>
                <div class="section-grid w100">
                    <div class="field full-width horizontal">
                        <div class="field-content">
                            <label for="nome">Nome:</label>
                            <div class="card-title">{{ $visitante->nome }}</div>
                        </div>
                    </div>
                    <div class="field">
                        <label for="data_nascimento">Data da visita: </label>
                        <div class="card-title">
                            @php
                                $timestamp = strtotime($visitante->data_visita);
                                $data_visita = date('d/m/Y',$timestamp);
                            @endphp
                            {{$data_visita ?? '-'}}</div>
                    </div>
                    <div class="field">
                        <label for="telefone">Telefone: </label>
                        <div class="card-title">{{$visitante->telefone ?? '-'}}</div>
                    </div>
                    <div class="field">
                        <label for="telefone">Situação do visitante: </label>
                        <div class="card-title">{{$visitante->sit_visitante->titulo ?? '-'}}</div>
                    </div>
                    <div class="field">
                        <label for="telefone">Observações: </label>
                        <div class="card-title">{{$visitante->observacoes ?? '-'}}</div>
                    </div>
                </div>
            </div>{{-- section --}}
        
            <div class="form-options nao-imprimir limit-80">
                <a href="/visitantes/editar/{{$visitante->id}}"><button class="btn" type="button"><i class="bi bi-pencil-square"></i> Editar</button></a>   
                <button class="btn imprimir" type="button"><i class="bi bi-printer"></i> Imprimir</button>
                <form action="{{route('visitantes.destroy', $visitante->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button class="btn" type="submit"><i class="bi bi-trash"></i> Remover</button>
                </form>
                <button type="button" onclick="window.history.back()" class="btn"><i class="bi bi-arrow-return-left"></i> Voltar</button>
            </div>{{-- form-options --}}
        </div><!--info-->
    </form>
</div>

@endsection

@push('scripts')
    <script>

        $(document).ready(function(){
            
            $('.imprimir').click(function(event) {
                event.preventDefault();
                window.print();
            });
       
        });
        
    </script>
@endpush