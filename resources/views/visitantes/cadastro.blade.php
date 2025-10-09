@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')
@php
    $visitors = trans('visitors');
    $common = $visitors['common'];
    $page = $visitors['cadastro'];
@endphp

@if ($errors->any())
    <div class="msg">
        <div class="error">
            {{ $errors->first() }}
        </div>
    </div>
@endif

<div class="container">
    <h1>{{ $page['title'] }}</h1>
    <div class="info">
        <h3>{{ $page['section'] }}</h3>
        <form action="{{ route('visitantes.store') }}" method="post">
            @csrf
            <div class="form-control">
                <div class="form-item">
                    <label for="visitante-nome">{{ $common['fields']['name'] }}</label>
                    <input type="text" id="visitante-nome" name="nome" value="{{ old('nome') }}" placeholder="{{ $common['placeholders']['name'] }}" required>
                </div>
                <div class="form-item">
                    <label for="visitante-telefone">{{ $common['fields']['phone'] }}</label>
                    <input type="tel" id="visitante-telefone" name="telefone" value="{{ old('telefone') }}" placeholder="{{ $common['placeholders']['phone'] }}" required>
                </div>
                <div class="form-item">
                    <label for="visitante-data">{{ $common['fields']['visit_date'] }}</label>
                    <input type="date" id="visitante-data" name="data_visita" value="{{ old('data_visita', now()->format('Y-m-d')) }}" required>
                </div>
                <div class="form-item">
                    <label for="visitante-situacao">{{ $common['fields']['status'] }}</label>
                    <select name="situacao" id="visitante-situacao" required>
                        <option value="">{{ $common['placeholders']['search_name'] }}</option>
                        @foreach ($situacao_visitante as $item)
                            <option value="{{ $item->id }}" @selected(old('situacao') == $item->id)>{{ $item->titulo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-item">
                    <label for="visitante-observacoes">{{ $common['fields']['notes'] }}</label>
                    <textarea id="visitante-observacoes" name="observacoes" placeholder="{{ $common['placeholders']['notes'] }}">{{ old('observacoes') }}</textarea>
                </div>
                <div class="form-options">
                    <button class="btn" type="submit"><i class="bi bi-plus-circle"></i> {{ $common['buttons']['save'] }}</button>
                    <a href="{{ route('visitantes.historico') }}" class="btn"><i class="bi bi-card-list"></i> {{ $common['buttons']['history'] }}</a>
                    <button type="button" class="btn" onclick="window.history.back()"><i class="bi bi-x-circle"></i> {{ $common['buttons']['cancel'] }}</button>
                </div>
            </div>
        </form>
        @if (module_enabled('recados'))
            <a href="{{ url('/recados/adicionar') }}" class="float-btn" title="Recados"><i class="bi bi-chat-left-dots"></i></a>
        @endif
        <div class="clear"></div>
    </div>
</div>
@endsection
