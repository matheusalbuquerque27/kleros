@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')
@php
    $visitors = trans('visitors');
    $editTexts = $visitors['edit'];
@endphp

<div class="container">
    <h1>{{ $editTexts['title'] }}</h1>
    @include('visitantes.includes.form_editar', ['visitante' => $visitante, 'situacao_visitante' => $situacao_visitante])
</div>
@endsection
