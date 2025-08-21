@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')

<div class="container">
    <h1>Informações de Membro</h1>
    
    <form action="{{ route('membros.destroy', $membro->id) }}" method="post" onsubmit="return handleSubmit(event, this, 'Tem certeza que deseja excluir?')">
        @csrf
        <div class="data-view">
            <div class="section">
                <h3>Informações básicas</h3>
                <div class="section-grid w100">
                    <div class="field full-width horizontal">
                        <div class="field-content">
                            <label for="nome">Nome completo:</label>
                            <div class="card-title">{{ $membro->nome }}</div>
                        </div>
                        <img class="avatar_perfil" src="{{ asset('storage/' . ($membro->foto ?? 'images/newuser.png')) }}" alt="Foto de perfil">

                    </div>
                    <div class="field">
                        <label for="rg">RG: </label>
                        <div class="card-title">{{$membro->rg ?? '-'}}</div>
                    </div>
                    <div class="field">
                        <label for="cpf">CPF: </label>
                        <div class="card-title">{{$membro->cpf ?? '-'}}</div>
                    </div>
                    <div class="field">
                        <label for="data_nascimento">Data de nascimento: </label>
                        <div class="card-title">
                            @php
                                $timestamp = strtotime($membro->data_nascimento);
                                $data_nascimento = date('d/m/Y',$timestamp);
                            @endphp
                            {{$data_nascimento ?? '-'}}</div>
                    </div>
                    <div class="field">
                        <label for="telefone">Telefone: </label>
                        <div class="card-title">{{$membro->telefone ?? '-'}}</div>
                    </div>
                    <div class="field">
                        <label for="estado_civil">Estado civil: </label>
                        <div class="card-title">{{optional($membro->estadoCiv)->titulo  ?? '-'}}</div>
                    </div>
                    <div class="field">
                        <label for="escolaridade">Escolaridade: </label>
                        <div class="card-title">{{optional($membro->escolaridade)->titulo ?? '-'}}</div>
                    </div>
                    <div class="field">
                        <label for="profissao">Profissão: </label>
                        <div class="card-title">{{$membro->profissao ?? '-'}}</div>
                    </div>
                </div>
            </div>{{-- section --}}
        
            <div class="section">
                <h3>Informações de endereço</h3>
                <div class="section-grid">
                    <div class="field">
                        <label for="endereco">Endereço: </label>
                        <div class="card-title">{{$membro->endereco ?? '-'}}</div>
                    </div>
                    <div class="field">
                        <label for="numero">Número: </label>
                        <div class="card-title">{{$membro->numero ?? '-'}}</div>
                    </div>
                    <div class="field">
                        <label for="complemento">Complemento: </label>
                        <div class="card-title">{{$membro->complemento ?? '-'}}</div>
                    </div>
                    <div class="field">
                        <label for="bairro">Bairro: </label>
                        <div class="card-title">{{$membro->bairro ?? '-'}}</div>
                    </div>
                    <div class="field">
                        <label for="cep">CEP: </label>
                        <div class="card-title">{{$membro->cep ?? '-'}}</div>
                    </div>
                </div>
            </div>{{-- section --}}

            <div class="section">
                <h3>Informações específicas</h3>
                <div class="section-grid">
                    <div class="field">
                        <label for="data_batismo">Data de Batismo: </label>
                        <div class="card-title">
                            @php
                                $timestamp = strtotime($membro->data_batismo);
                                $data_batismo = date('d/m/Y',$timestamp);
                            @endphp
                            {{$data_batismo ?? '-'}}
                        </div>
                    </div>
                    <div class="field">
                        <label for="denominacao_origem">Denominação de Origem: </label>
                        <div class="card-title">{{$membro->denominacao_origem  ?? '-'}}</div>
                    </div>
                    <div class="field">
                        <label for="ministerio">Ministério: </label>
                        <div class="card-title">{{optional($membro->ministerio)->titulo  ?? '-'}}</div>
                    </div>
                    <div class="field">
                        <label for="data_consagracao">Data de Consagração: </label>
                        <div class="card-title">
                            @php
                                $timestamp = strtotime($membro->data_consagracao);
                                $data_consagracao = date('d/m/Y',$timestamp);
                            @endphp
                            {{$data_consagracao ?? '-'}}</div>
                    </div>
                </div>
            </div>{{-- section --}}

            <div class="section">
                <h3>Filiação</h3>
                <div class="section-grid">
                    <div class="field">
                        <label for="nome_paterno">Nome paterno: </label>
                        <div class="card-title">{{$membro->nome_paterno ?? '-'}}</div>
                    </div>
                    <div class="field">
                        <label for="nome_materno">Nome materno: </label>
                        <div class="card-title">{{$membro->nome_materno ?? '-'}}</div>
                    </div>
                </div>
            </div>{{-- form-control --}}
            <div class="form-options nao-imprimir limit-80">
                <a onclick="abrirJanelaModal('{{route('membros.editar', $membro->id)}}')"><button class="btn" type="button"><i class="bi bi-pencil-square"></i> Editar</button></a>   
                <button class="btn imprimir" type="button"><i class="bi bi-printer"></i> Imprimir</button>
                <form action="{{route('membros.destroy', $membro->id)}}" method="post">
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