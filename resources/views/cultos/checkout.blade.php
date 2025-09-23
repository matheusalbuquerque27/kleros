@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')

@if($errors->all())
<div class="msg">
    <div class="error">
        <ul>
            {{$errors->first()}}
        </ul>
    </div>
</div>
@endif

<div class="container">
    <h1>Registrar Culto</h1>
    <div class="info" id="registrar">
        <h3>Registro de Culto</h3>
        <form action="@if($culto == null) {{route('cultos.store')}} @else {{route('cultos.update', $culto->id)}} @endif" method="post">
            @csrf
            @if($culto != null)
                @method('PUT')
            @endif
            <div class="form-control">
                <div class="form-item">
                    <label for="data_culto">Data do culto: </label>
                    <input class="input-false" type="date" name="data_culto" id="" value="{{optional($culto)->data_culto}}" @readonly($culto != null)>
                </div>
                <div class="form-item">
                    <label for="preletor">Preletor: </label>
                    <input type="text" name="preletor" id="" value="{{optional($culto)->preletor}}">
                </div>
                <div class="form-item">
                    <label for="evento">Evento: </label>
                    <select name="evento_id" id="" @disabled($culto != null)>
                        <option value="">Selecione um evento cadastrado</option>
                        <option value="" @selected(optional($culto)->evento_id == '')>Nenhum</option>
                        @if($eventos)
                            @foreach ($eventos as $item)
                            <option value="{{$item->id}}" @selected(optional($culto)->evento_id == $item->id)>{{$item->titulo}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                @if($culto == null)
                <div class="form-item">
                    <div class="card">
                        <p>Não encontrou o evento? <a class="link-standard" href="/eventos/adicionar">Cadastrar aqui</a></p>
                    </div>
                </div>
                @endif

                <h3>Dados específicos</h3>

                <div class="form-item">
                    <label for="tema_sermao">Tema do sermão</label>
                    <input type="text" placeholder="Tema central do sermão" name="tema_sermao" value="{{optional($culto)->tema_sermao}}">
                </div>
                <div class="form-item">
                    <label for="texto_base">Texto-base</label>
                    <input type="text" placeholder="Texto-base do sermão" name="texto_base" value="{{optional($culto)->texto_base}}">
                </div>
                <div class="form-item">
                    <label for="quantidade_pessoas">Quantidade de pessoas</label>
                    <div class="form-square">
                        <div>
                            <label for="quantidade_adultos">Adultos</label>
                            <input type="number" placeholder="0" name="quantidade_adultos" value="{{optional($culto)->quant_adultos}}">
                        </div>
                        <div>
                            <label for="texto_base">Crianças</label>
                            <input type="number" placeholder="0" name="texto_base" value="{{optional($culto)->quant_criancas}}">
                        </div>
                        <div>
                            <label for="quantidade_visitantes">Visitantes</label>
                            <input type="number" placeholder="0" name="quantidade_visitantes" value="{{optional($culto)->quant_visitantes}}">
                        </div>
                    </div>
                </div>
                <div class="form-item">
                    <label for="observacoes">Observacões</label>
                    <textarea name="" id="" cols="30" rows="3" placeholder="Observações gerais sobre o culto">{{optional($culto)->observacoes}}</textarea>
                </div>
                <div class="form-options">
                    <button class="btn" type="submit"><i class="bi bi-plus-circle"></i> Salvar Culto</button>
                    
                    @if($culto != null && module_enabled('recados') && Route::has('recados.listar'))
                    <a href="{{ route('recados.listar', $culto->id) }}"><button class="btn" type="button"><i class="bi bi-chat-left-dots"></i> Ver Recados</button></a>
                    @endif
                    
                    <button type="button" class="btn" onclick="window.history.back()"><i class="bi bi-arrow-return-left"></i> Voltar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
