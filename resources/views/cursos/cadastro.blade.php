@extends('layouts.main')

@section('title', 'Novo Curso - AD Jerusalém')

@section('content')

@if($errors->all())
<div class="msg">
    <div class="error">
        <ul>
            {{ $errors->first() }}
        </ul>
    </div>
</div>
@endif

<div class="container">
    <h1>Novo Curso</h1>
    <div class="info">
        <h3>Adicionar</h3>
        <form action="/cursos" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-control">

                <div class="form-item">
                    <label for="titulo">Título do curso: </label>
                    <input type="text" name="titulo" id="titulo" placeholder="Título do curso" required>
                </div>

                <div class="form-item">
                    <label for="descricao">Descrição: </label>
                    <textarea name="descricao" id="descricao" placeholder="Descrição do curso (opcional)"></textarea>
                </div>

                <div class="form-item">
                    <label for="publico">Visibilidade do curso: </label>
                    <div class="form-square">
                        <div>
                            <input type="radio" id="publico" name="publico" value="1">
                            <label for="publico">Público - Acesso livre</label>
                        </div>
                        <div>
                            <input type="radio" id="privado" name="publico" value="0" checked>
                            <label for="privado">Privado - Somente definidos</label>
                        </div>
                    </div>
                </div>

                <div class="form-item">
                    <label for="ativo">Status do curso: </label>
                    <div class="form-square">
                        <div>
                            <input type="radio" id="ativo" name="ativo" value="1" checked>
                            <label for="ativo">Ativo</label>
                        </div>
                        <div>
                            <input type="radio" id="inativo" name="ativo" value="0">
                            <label for="inativo">Inativo</label>
                        </div>
                    </div>
                </div>

                <div class="form-item">
                    <label for="icone">Ícone do curso: </label>
                    <input type="file" name="icone" id="icone" accept="image/*">
                    <small>Opcional - ícone ilustrativo para o curso</small>
                </div>

                <div class="form-options">
                    <button class="btn" type="submit"><i class="bi bi-plus-circle"></i> Adicionar Curso</button>
                    <a href="/cadastros#cursos"><button type="button" class="btn"><i class="bi bi-x-circle"></i> Cancelar</button></a>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection