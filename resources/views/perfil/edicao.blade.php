@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')

<div class="container">
    <h1>Editar Perfil</h1>
    <div class="info">
        <form action="{{route('perfil.update', $membro->id)}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="tabs">
                <!-- Menu de abas -->
                <ul class="tab-menu">
                    <li class="active" data-tab="pessoais"><i class="bi bi-person"></i> Dados Pessoais</li>
                    <li data-tab="bio"><i class="bi bi-journal-text"></i> Biografia</li>
                    <li data-tab="tab-foto"><i class="bi bi-image"></i> Foto</li>
                    <li data-tab="seguranca"><i class="bi bi-shield-lock"></i> Segurança</li>
                </ul>

                <!-- Conteúdo das abas -->
                <div class="tab-content card">

                    <!-- Aba 1 -->
                    <div id="pessoais" class="tab-pane form-control active">
                        <div class="form-item">
                            <label for="nome">Nome completo</label>
                            <input type="text" id="nome" name="nome" 
                                value="{{ old('nome', optional($membro)->nome) }}" required>
                        </div>
                        <div class="form-item">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" 
                                value="{{ old('email', optional(auth()->user())->email) }}" required>
                        </div>
                        <div class="form-item">
                            <label for="telefone">Telefone</label>
                            <input type="tel" id="telefone" name="telefone" 
                                value="{{ old('telefone', optional($membro)->telefone) }}" required>
                        </div>
                    </div>

                    <!-- Aba 2 -->
                    <div id="seguranca" class="tab-pane form-control">
                        <h4>Modificar senha</h4>
                        <div class="form-item">
                            <label for="senha_atual">Senha atual</label>
                            <input type="password" id="senha_atual" name="senha_atual" 
                                placeholder="Senha atual">
                        </div>
                        <div class="form-item">
                            <label for="nova_senha">Nova senha</label>
                            <input type="password" id="nova_senha" name="nova_senha" 
                                placeholder="Nova senha">
                        </div>
                    </div>

                    <!-- Aba 3 -->
                    <div id="bio" class="tab-pane form-control">
                        <div class="form-item">
                            <label for="bio">Biografia</label>
                            <textarea id="biografia" name="biografia" rows="6" placeholder="Escreva sua biografia para que outros te conheçam melhor">{{ old('bio', optional($membro)->biografia) }}</textarea>
                        </div>
                    </div>

                    <!-- Aba 4 -->
                    <div id="tab-foto" class="tab-pane form-control">
                        <div class="form-item">
                            <label for="foto">Foto de perfil</label>

                            @if(!empty($membro->foto))
                                <img class="image-small" id="foto-img" 
                                    src="{{ Storage::url($membro->foto) }}" 
                                    alt="Foto de perfil">
                            @else
                                <img class="image-small" id="foto-img" 
                                    src="{{ asset('storage/images/newuser.png') }}" 
                                    alt="Sem foto">
                            @endif

                            <div class="foto-upload">
                                <span id="file-logo">Nenhum arquivo selecionado</span>
                                <label for="foto" class="btn-line"><i class="bi bi-upload"></i> Upload</label>
                                <input type="file" name="foto" id="foto">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-options">
                    <button type="submit" class="btn"><i class="bi bi-arrow-clockwise"></i> Atualizar</button>
                </div>
               
            </div>
            </div>
        </form>
        @include('noticias.includes.destaques')
    </div>
</div>

<!-- Estilo básico -->
<style>
/* Abas */
.tab-menu {
    display: flex;
    justify-content: left;
    list-style: none;
    padding: 0;
    margin: 0 0 15px 0;
    border-bottom: 2px solid var(--secondary-color);
}
.tab-menu li {
    padding: 6px 12px;
    cursor: pointer;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    margin-right: 4px;
    border-radius: 8px 8px 0 0;
    transition: all .3s;
    font-weight: 500;
}
.tab-menu li:hover {
    background: var(--secondary-color);
    color: #fff;
}
.tab-menu li.active {
    background: var(--secondary-color);
    color: #fff;
    font-weight: bold;
}

.perfil-tabs {
    width: 100%;
    max-width: 860px;
    margin: 0 auto;
}
.perfil-tabs .tabs {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.perfil-tabs .tab-content.card {
    width: 100%;
    box-sizing: border-box;
}

/* Conteúdo das abas */
.card {
    border-radius: 0 8px 8px 8px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.tab-pane {
    display: none;
    animation: fadeIn .4s ease-in-out;
}
.tab-pane.active {
    display: block;
}


.form-item input:focus, 
.form-item textarea:focus {
    border: 1px solid var(--primary-color);
    outline: none;
}

/* Foto */
.image-preview {
    max-width: 150px;
    border-radius: 10px;
    margin: 10px 0;
    display: block;
}
.foto-upload {
    display: flex;
    align-items: center;
    gap: 10px;
}
.btn-upload {
    background: var(--secondary-color);
    color: #fff;
    padding: 8px 14px;
    border-radius: 6px;
    cursor: pointer;
    transition: background .3s;
}
.btn-upload:hover {
    background: var(--primary-color);
}
#foto {
    display: none;
}

/* Botão salvar */
.form-options {
    text-align: center;
    margin-top: 20px;
}

/* Animação suave */
@keyframes fadeIn {
    from {opacity: 0; transform: translateY(10px);}
    to {opacity: 1; transform: translateY(0);}
}
</style>

@endsection
