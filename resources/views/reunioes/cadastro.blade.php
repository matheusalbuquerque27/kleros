@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')
    <div class="container">
        @if($errors->any())
            <div class="msg">
                <div class="error">
                    <ul>
                        <li>{{ $errors->first() }}</li>
                    </ul>
                </div>
            </div>
        @endif

        @include('reunioes.includes.form_criar')
    </div>
@endsection
