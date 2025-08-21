@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')

<div class="container">
    <h1>Editar Perfil</h1>
    <div class="info">
        <h3>Minhas informações</h3>
        <form action="{{route('perfil.update', $membro->id)}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-control">
                <div class="form-item">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="{{ old('nome', optional($membro)->nome) }}" required>
                </div>
                <div class="form-item">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="{{ old('email', optional(auth()->user())->email) }}" required>
                </div>
                <div class="form-item">
                    <label for="email">Telefone:</label>
                    <input type="tel" id="telefone" name="telefone" value="{{ old('telefone', optional($membro)->telefone) }}" required>
                </div>
                <div class="form-item">
                    <label for="bio">Biografia:</label>
                    <textarea id="bio" name="bio" rows="4">{{ old('bio', optional($membro)->bio) }}</textarea>
                </div>
                <div class="form-item">
                    <label for="foto">Foto de perfil</label>
                    <img class="image-small" id="foto-img" src="{{asset('storage/'.$membro->foto)}}" alt="">
                    <div class="foto">
                        <span id="file-logo">Nenhum arquivo selecionado</span>
                        <label for="foto" class="btn-line"><i class="bi bi-upload"></i> Upload</label>
                        <input type="file" name="foto" id="foto" url="">
                    </div>
                </div>
                <div class="form-options">
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection