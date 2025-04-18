@extends('layouts.main')

@section('title', 'Informações de Membro - AD Jerusalém')

@section('content')

<div class="container">
    <h1>Informações de Membro</h1>
    <form action="/membros/editar" method="post">
        @csrf
        <div class="info form-control">
            <h3>Informações básicas</h3>
            <div class="form-control">
                <div class="form-block ">
                    <div class="form-item">
                        <label for="nome">Nome completo: </label>
                        <div class="card-title">{{$membro->nome}}</div>
                    </div>
                    <div class="form-item">
                        <label for="rg">RG: </label>
                        <div class="card-title">{{$membro->rg}}</div>
                    </div>
                    <div class="form-item">
                        <label for="cpf">CPF: </label>
                        <div class="card-title">{{$membro->cpf}}</div>
                    </div>
                    <div class="form-item">
                        <label for="data_nascimento">Data de nascimento: </label>
                        <div class="card-title">
                            @php
                                $timestamp = strtotime($membro->data_nascimento);
                                $data_nascimento = date('d/m/Y',$timestamp);
                            @endphp
                            {{$data_nascimento}}</div>
                    </div>
                    <div class="form-item">
                        <label for="telefone">Telefone: </label>
                        <div class="card-title">{{$membro->telefone}}</div>
                    </div>
                    <div class="form-item">
                        <label for="estado_civil">Estado civil: </label>
                        <div class="card-title">{{$membro->estadoCiv->titulo}}</div>
                    </div>
                    <div class="form-item">
                        <label for="escolaridade">Escolaridade: </label>
                        <div class="card-title">{{$membro->escolaridade->titulo}}</div>
                    </div>
                    <div class="form-item">
                        <label for="profissao">Profissão: </label>
                        <div class="card-title">{{$membro->profissao}}</div>
                    </div>
                </div>
            </div>{{-- form-control --}}
            <h3>Informações de endereço</h3>
            <div class="form-control">
                <div class="form-block">
                    <div class="form-item">
                        <label for="endereco">Endereço: </label>
                        <div class="card-title">{{$membro->endereco}}</div>
                    </div>
                    <div class="form-item">
                        <label for="numero">Número: </label>
                        <div class="card-title">{{$membro->numero}}</div>
                    </div>
                    <div class="form-item">
                        <label for="bairro">Bairro: </label>
                        <div class="card-title">{{$membro->bairro}}</div>
                    </div>
                </div>
            </div>{{-- form-control --}}

            <h3>Informações específicas</h3>
            <div class="form-control">
                <div class="form-block">
                    <div class="form-item">
                        <label for="data_batismo">Data de Batismo: </label>
                        <div class="card-title">
                            @php
                                $timestamp = strtotime($membro->data_batismo);
                                $data_batismo = date('d/m/Y',$timestamp);
                            @endphp
                            {{$data_batismo}}
                        </div>
                    </div>
                    <div class="form-item">
                        <label for="denominacao_origem">Denominação de Origem: </label>
                        <div class="card-title">{{$membro->denominacao_origem}}</div>
                    </div>
                    <div class="form-item">
                        <label for="ministerio">Ministério: </label>
                        <div class="card-title">{{$membro->ministerio->titulo}}</div>
                    </div>
                    <div class="form-item">
                        <label for="ministerio">Data de Consagração: </label>
                        <div class="card-title">00/00/0000</div>
                    </div>
                </div>
            </div>{{-- form-control --}}

            <h3>Filiação</h3>
            <div class="form-control">
                <div class="form-block">
                    <div class="form-item">
                        <label for="nome_paterno">Nome paterno: </label>
                        <div class="card-title">{{$membro->nome_paterno}}</div>
                    </div>
                    <div class="form-item">
                        <label for="nome_materno">Nome materno: </label>
                        <div class="card-title">{{$membro->nome_materno}}</div>
                    </div>
                </div>
            </div>{{-- form-control --}}
            <div class="form-options nao-imprimir">
                <a href="/membros/editar/{{$membro->id}}"></a><button class="btn" type="submit"><i class="bi bi-pencil-square"></i> Editar</button>
                <button class="btn imprimir" type="button"><i class="bi bi-printer"></i> Imprimir</button>
                <button type="button" onclick="window.history.back()" class="btn"><i class="bi bi-arrow-return-left"></i> Voltar</button></a>
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