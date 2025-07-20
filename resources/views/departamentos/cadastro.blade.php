@extends('layouts.main')

@section('content')

@if($errors->any())
<div class="msg">
    <div class="error">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif
<div class="container">
    <h1>Novo Departamento</h1>
    <form action="{{ route('departamentos.store') }}" method="POST">
        @csrf
        <div class="form-control">
            <div class="form-item">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required>
        </div>
        </div>
        <div class="form-options">
            <button type="submit" class="btn btn-primary">Criar Departamento</button>
            <a href="{{ route('cadastros.index') }}"><button class="btn" type="button">Voltar</button></a>
        </div>
    
    </form>
</div>
@endsection