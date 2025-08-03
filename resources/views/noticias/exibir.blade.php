@extends('layouts.main')

@section('title', 'Notícias Cristãs')

@section('content')

<div class="container">
    <h1>Notícias Cristãs</h1>
    <div class="info">
        <div class="data-view">
            <div class="section">
                <h3>Informações básicas</h3>
                <div class="section-grid w100">
                    <div class="field full-width horizontal">
                        <div class="field-content">
                            <label for="nome">Nome completo:</label>
                            <div class="card-title">{{ $membro->nome }}</div>
                        </div>
                        <img class="avatar_perfil" src="{{ asset('storage/' . $membro->foto) }}" alt="Foto de perfil">

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
        
            <div class="form-options nao-imprimir limit-80">
                <a href="/"><button class="btn" type="button"><i class="bi bi-share"></i> Compartilhar</button></a>   
                <button type="button" onclick="window.history.back()" class="btn"><i class="bi bi-arrow-return-left"></i> Voltar</button></a>
            </div>{{-- form-options --}}
        </div><!--info-->
    </div>
</div>

@endsection