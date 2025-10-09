@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')
@php
    $edit = trans('congregations_edit');
    $tabs = $edit['tabs'];
    $sections = $edit['sections'];
    $placeholders = $edit['placeholders'];
    $scripts = $edit['scripts'];
@endphp

<div class="container">
    <h1>{{ $edit['title'] }}</h1>
    <form action="{{ url("/configuracoes/{$congregacao->id}") }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="info">
            <div class="tabs">
                <ul class="tab-menu">
                    <li class="active" data-tab="geral"><i class="bi bi-person"></i> {{ $tabs['general'] }}</li>
                    <li data-tab="visual"><i class="bi bi-journal-text"></i> {{ $tabs['visual'] }}</li>
                    <li data-tab="administrativo"><i class="bi bi-shield-lock"></i> {{ $tabs['administrative'] }}</li>
                </ul>
                <div class="tab-content card">
                    {{-- Dados Gerais --}}
                    <div id="geral" class="tab-pane form-control active">
                        <h3>{{ $sections['institutional']['title'] }}</h3>
                        <div class="form-control">
                            <div class="form-item">
                                <label for="identificacao">{{ $sections['institutional']['fields']['identification'] }}</label>
                                <input type="text" name="identificacao" id="identificacao" value="{{ $congregacao->identificacao }}">
                            </div>
                            <div class="form-item">
                                <label for="nome_curto">{{ $sections['institutional']['fields']['short_name'] }}</label>
                                <input type="text" name="nome_curto" id="nome_curto" value="{{ old('nome_curto', $congregacao->nome_curto) }}"
                                    placeholder="{{ $placeholders['short_name'] ?? '' }}">
                            </div>
                            <div class="form-item">
                                <label for="cnpj">{{ $sections['institutional']['fields']['cnpj'] }}</label>
                                <input type="text" name="cnpj" id="cnpj" value="{{ $congregacao->cnpj }}" placeholder="{{ $placeholders['cnpj'] }}">
                            </div>
                            <div class="form-item">
                                <label for="email">{{ $sections['institutional']['fields']['email'] }}</label>
                                <input type="email" name="email" id="email" value="{{ $congregacao->email }}" autocomplete="email" placeholder="{{ $placeholders['email'] }}">
                            </div>
                            <div class="form-item">
                                <label for="telefone">{{ $sections['institutional']['fields']['phone'] }}</label>
                                <input type="tel" name="telefone" id="telefone" value="{{ $congregacao->telefone }}" placeholder="{{ $placeholders['phone'] }}">
                            </div>
                        </div>
                        <h3>{{ $sections['location']['title'] }}</h3>
                        <div class="form-control">
                            <div class="form-item">
                                <label for="endereco">{{ $sections['location']['fields']['address'] }}</label>
                                <input type="text" name="endereco" id="endereco" value="{{ $congregacao->endereco }}">
                            </div>
                            <div class="form-item">
                                <label for="numero">{{ $sections['location']['fields']['number'] }}</label>
                                <input type="text" name="numero" id="numero" value="{{ $congregacao->numero }}">
                            </div>
                            <div class="form-item">
                                <label for="complemento">{{ $sections['location']['fields']['complement'] }}</label>
                                <input type="text" name="complemento" id="complemento" value="{{ $congregacao->complemento }}">
                            </div>
                            <div class="form-item">
                                <label for="bairro">{{ $sections['location']['fields']['district'] }}</label>
                                <input type="text" name="bairro" id="bairro" value="{{ $congregacao->bairro }}">
                            </div>
                            <div class="form-item">
                                <label for="pais">{{ $sections['location']['fields']['country'] }}</label>
                                <select name="pais" id="pais">
                                    <option value="">{{ $sections['location']['placeholders']['country'] }}</option>
                                    @foreach($paises as $item)
                                        <option value="{{ $item->id }}" @selected($congregacao->pais_id == $item->id)>{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-item">
                                <label for="estado">{{ $sections['location']['fields']['state'] }}</label>
                                <select name="estado" id="estado">
                                    <option value="">{{ $sections['location']['placeholders']['state'] }}</option>
                                </select>
                            </div>
                            <div class="form-item">
                                <label for="cidade">{{ $sections['location']['fields']['city'] }}</label>
                                <select name="cidade" id="cidade">
                                    <option value="">{{ $sections['location']['placeholders']['city'] }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Visual --}}
                    <div id="visual" class="tab-pane form-control">
                        <h3>{{ $sections['visual']['title'] }}</h3>
                        <div class="form-control">
                            <h4>{{ $sections['visual']['files']['title'] }}</h4>
                            <div class="form-item">
                                <label for="logo">{{ $sections['visual']['files']['logo'] }}</label>
                                <img class="image-small" id="logo-img" src="{{ asset('storage/' . $congregacao->config->logo_caminho) }}" alt="">
                                <div class="logo">
                                    <span id="file-logo">{{ $scripts['no_file'] }}</span>
                                    <label for="logo" class="btn-line"><i class="bi bi-upload"></i> {{ $sections['visual']['files']['upload'] }}</label>
                                    <input type="file" name="logo" id="logo" url="">
                                    <input type="hidden" name="logo_acervo" id="logo_acervo">
                                </div>
                            </div>
                            <div class="form-item">
                                <label for="banner">{{ $sections['visual']['files']['banner'] }}</label>
                                <img class="image-small" id="banner-img" src="{{ asset('storage/' . $congregacao->config->banner_caminho) }}" alt="">
                                <div class="banner">
                                    <span id="file-banner">{{ $scripts['no_file'] }}</span>
                                    <label for="banner" class="btn-line"><i class="bi bi-upload"></i> {{ $sections['visual']['files']['upload'] }}</label>
                                    <input type="file" name="banner" id="banner" url="">
                                    <input type="hidden" name="banner_acervo" id="banner_acervo">
                                </div>
                            </div>
                        </div>

                        <h3>{{ $sections['visual']['colors']['title'] }}</h3>
                        <p class="hint">{{ $sections['visual']['colors']['description'] }}</p>
                        <div class="form-control">
                            <div class="form-item">
                                <label for="cor_primaria">{{ $sections['visual']['colors']['primary'] }}</label>
                                <input type="color" name="conjunto_cores[primaria]" id="cor_primaria" value="{{ $congregacao->config->conjunto_cores['primaria'] }}">
                            </div>
                            <div class="form-item">
                                <label for="cor_secundaria">{{ $sections['visual']['colors']['secondary'] }}</label>
                                <input type="color" name="conjunto_cores[secundaria]" id="cor_secundaria" value="{{ $congregacao->config->conjunto_cores['secundaria'] }}">
                            </div>
                            <div class="form-item">
                                <label for="cor_terciaria">{{ $sections['visual']['colors']['accent'] }}</label>
                                <input type="color" name="conjunto_cores[terciaria]" id="cor_terciaria" value="{{ $congregacao->config->conjunto_cores['terciaria'] }}">
                            </div>
                            <div class="form-item">
                                <label for="fonte">{{ $sections['visual']['colors']['font'] }}</label>
                                <select name="font_family" id="fonte">
                                    @foreach ($fontes as $fonte)
                                        <option value="{{ $fonte }}" @selected($congregacao->config->font_family === $fonte)>{{ $fonte }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-item">
                                <h4 class="w100 right">
                                    <div class="tag">{{ $sections['visual']['colors']['preview_label'] }}</div>
                                    <span class="right" id="font-preview">{{ $sections['visual']['colors']['preview_text'] }}</span>
                                </h4>
                            </div>
                        </div>

                        <h3>{{ $sections['visual']['themes']['title'] }}</h3>
                        <div class="form-control">
                            <div class="form-item">
                                <div class="form-square" id="tema">
                                    <div>
                                        <input type="radio" id="classico" name="tema" value="1" @checked(optional($congregacao->config->tema)->id == 1)>
                                        <label for="classico">{{ $sections['visual']['themes']['classic'] }}</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="moderno" name="tema" value="2" @checked(optional($congregacao->config->tema)->id == 2)>
                                        <label for="moderno">{{ $sections['visual']['themes']['modern'] }}</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="vintage" name="tema" value="3" @checked(optional($congregacao->config->tema)->id == 3)>
                                        <label for="vintage">{{ $sections['visual']['themes']['vintage'] }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Administração --}}
                    <div id="administrativo" class="tab-pane form-control">
                        <h3>{{ $sections['administrative']['title'] }}</h3>
                        <div class="form-control">
                            <div class="form-item">
                                <label for="agrupamentos">{{ $sections['administrative']['grouping'] }}</label>
                                <select name="agrupamentos" id="agrupamentos">
                                    @foreach($sections['administrative']['grouping_options'] as $value => $label)
                                        <option value="{{ $value }}" @selected(old('agrupamentos', $congregacao->config->agrupamentos) === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-item">
                                <label>{{ $sections['administrative']['cells']['label'] }}</label>
                                <div class="form-square">
                                    <div>
                                        <input type="radio" id="celula_ativo" name="celulas" value="1" @checked($congregacao->config->celulas == 1)>
                                        <label for="celula_ativo">{{ $sections['administrative']['cells']['active'] }}</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="celula_inativo" name="celulas" value="0" @checked($congregacao->config->celulas == 0)>
                                        <label for="celula_inativo">{{ $sections['administrative']['cells']['inactive'] }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-item">
                                <label>{{ $sections['administrative']['language']['label'] }}</label>
                                <select name="language" id="language">
                                    @foreach($languageOptions as $value => $label)
                                        <option value="{{ $value }}" @selected(old('language', $congregacao->language) === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-options">
                        <button class="btn" type="submit"><i class="bi bi-arrow-clockwise"></i> {{ $edit['buttons']['update'] }}</button>
                        <button class="btn" type="button"><i class="bi bi-skip-backward"></i> {{ $edit['buttons']['restore'] }}</button>
                        <a href="{{ url('/') }}"><button type="button" class="btn"><i class="bi bi-arrow-return-left"></i> {{ $edit['buttons']['back'] }}</button></a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
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
    .image-small {
        max-width: 150px;
        border-radius: 10px;
        margin: 10px 0;
        display: block;
    }
    .form-options {
        text-align: center;
        margin-top: 20px;
    }
    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(10px);}
        to {opacity: 1; transform: translateY(0);}
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const translations = @json($scripts);

        const tabs = document.querySelectorAll('.tab-menu li');
        const panes = document.querySelectorAll('.tab-pane');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active'));
                panes.forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                const target = this.getAttribute('data-tab');
                document.getElementById(target)?.classList.add('active');
            });
        });

        $('#fonte').on('change', function() {
            $('#font-preview').css('font-family', this.value);
        });

        $('#logo').on('change', function() {
            const fileName = this.files[0] ? this.files[0].name : translations.no_file;
            $('#file-logo').text(fileName);
        });

        $('#banner').on('change', function() {
            const fileName = this.files[0] ? this.files[0].name : translations.no_file;
            $('#file-banner').text(fileName);
        });

        const paisSelect = document.getElementById('pais');
        const estadoSelect = document.getElementById('estado');
        const cidadeSelect = document.getElementById('cidade');
        const selectedPais = "{{ $congregacao->pais_id ?? '' }}";
        const selectedEstado = "{{ $congregacao->estado_id ?? '' }}";
        const selectedCidade = "{{ $congregacao->cidade_id ?? '' }}";

        if (paisSelect && estadoSelect && cidadeSelect) {
            paisSelect.addEventListener('change', function () {
                carregarEstados(this.value);
            });
            estadoSelect.addEventListener('change', function () {
                carregarCidades(this.value);
            });
            if (selectedPais) {
                carregarEstados(selectedPais, selectedEstado, () => {
                    if (selectedEstado) {
                        carregarCidades(selectedEstado, selectedCidade);
                    }
                });
            }
        }

        function carregarEstados(paisId, estadoId = null, callback = null) {
            estadoSelect.innerHTML = `<option value="">${translations.loading}</option>`;
            cidadeSelect.innerHTML = `<option value="">${translations.select_city}</option>`;

            if (!paisId) {
                estadoSelect.innerHTML = `<option value="">${translations.select_state}</option>`;
                return;
            }

            fetch(`/estados/${paisId}`)
                .then(res => res.json())
                .then(estados => {
                    estadoSelect.innerHTML = `<option value="">${translations.select_state}</option>`;
                    estados.forEach(estado => {
                        const selected = estadoId && Number(estado.id) === Number(estadoId) ? 'selected' : '';
                        estadoSelect.innerHTML += `<option value="${estado.id}" ${selected}>${estado.nome}</option>`;
                    });
                    if (callback) callback();
                });
        }

        function carregarCidades(estadoId, cidadeId = null) {
            cidadeSelect.innerHTML = `<option value="">${translations.loading}</option>`;

            if (!estadoId) {
                cidadeSelect.innerHTML = `<option value="">${translations.select_city}</option>`;
                return;
            }

            fetch(`/cidades/${estadoId}`)
                .then(res => res.json())
                .then(cidades => {
                    cidadeSelect.innerHTML = `<option value="">${translations.select_city}</option>`;
                    cidades.forEach(cidade => {
                        const selected = cidadeId && Number(cidade.id) === Number(cidadeId) ? 'selected' : '';
                        cidadeSelect.innerHTML += `<option value="${cidade.id}" ${selected}>${cidade.nome}</option>`;
                    });
                });
        }
    });
</script>
@endpush
@endsection
