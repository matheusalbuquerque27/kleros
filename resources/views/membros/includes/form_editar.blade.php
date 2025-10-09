@php
    $members = trans('members');
    $common = $members['common'];
    $edit = $members['edit'];
@endphp

<h1>{{ $edit['title'] }}</h1>
<div class="info">
    <form action="{{ route('membros.atualizar', $membro->id) }}" method="post">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="tabs">
            <ul class="tab-menu">
                <li class="active" data-tab="membro-dados"><i class="bi bi-person-badge"></i> {{ $edit['tabs']['personal'] }}</li>
                <li data-tab="membro-endereco"><i class="bi bi-geo-alt"></i> {{ $edit['tabs']['address'] }}</li>
                <li data-tab="membro-outros"><i class="bi bi-people"></i> {{ $edit['tabs']['other'] }}</li>
            </ul>

            <div class="tab-content card">
                <div id="membro-dados" class="tab-pane form-control active">
                    <div class="form-item">
                        <label for="nome">{{ $common['fields']['name'] }}:</label>
                        <input type="text" name="nome" id="nome" placeholder="{{ $common['placeholders']['name'] }}" value="{{ old('nome', $membro->nome) }}" required>
                        @error('nome')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-item">
                        <label for="rg">{{ $common['fields']['rg'] }}:</label>
                        <input type="text" name="rg" id="rg" placeholder="{{ $common['placeholders']['rg'] }}" value="{{ old('rg', $membro->rg) }}">
                    </div>
                    <div class="form-item">
                        <label for="cpf">{{ $common['fields']['cpf'] }}:</label>
                        <input type="text" name="cpf" id="cpf" placeholder="{{ $common['placeholders']['cpf'] ?? $common['fields']['cpf'] }}" value="{{ old('cpf', $membro->cpf) }}">
                    </div>
                    <div class="form-item">
                        <label for="data_nascimento">{{ $common['fields']['birthdate'] }}:</label>
                        <input type="date" name="data_nascimento" id="data_nascimento" value="{{ old('data_nascimento', $membro->data_nascimento) }}" required>
                    </div>
                    <div class="form-item">
                        <label for="telefone">{{ $common['fields']['phone'] }}:</label>
                        <input type="text" name="telefone" id="telefone" placeholder="{{ $common['placeholders']['phone'] }}" value="{{ old('telefone', $membro->telefone) }}" required>
                    </div>
                    <div class="form-item">
                        <label for="estado_civil">{{ $common['fields']['marital_status'] }}:</label>
                        <select name="estado_civil" id="estado_civil">
                            @foreach ($estado_civil as $item)
                                <option value="{{ $item->id }}" @selected(old('estado_civil', $membro->estado_civ_id) == $item->id)>
                                    {{ $item->titulo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-item">
                        <label for="escolaridade">{{ $common['fields']['education'] }}:</label>
                        <select name="escolaridade" id="escolaridade">
                            @foreach ($escolaridade as $item)
                                <option value="{{ $item->id }}" @selected(old('escolaridade', $membro->escolaridade_id) == $item->id)>
                                    {{ $item->titulo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-item">
                        <label for="profissao">{{ $common['fields']['profession'] }}:</label>
                        <input type="text" name="profissao" id="profissao" placeholder="{{ $common['placeholders']['profession'] ?? $common['fields']['profession'] }}" value="{{ old('profissao', $membro->profissao) }}">
                    </div>
                </div>

                <div id="membro-endereco" class="tab-pane form-control">
                    <div class="form-item">
                        <label for="endereco">{{ $common['fields']['address'] }}:</label>
                        <input type="text" name="endereco" id="endereco" placeholder="{{ $common['placeholders']['address'] }}" value="{{ old('endereco', $membro->endereco) }}">
                    </div>
                    <div class="form-item">
                        <label for="numero">{{ $common['fields']['number'] }}:</label>
                        <input type="text" name="numero" id="numero" placeholder="{{ $common['placeholders']['number'] }}" value="{{ old('numero', $membro->numero) }}">
                    </div>
                    <div class="form-item">
                        <label for="bairro">{{ $common['fields']['district'] }}:</label>
                        <input type="text" name="bairro" id="bairro" placeholder="{{ $common['placeholders']['district'] }}" value="{{ old('bairro', $membro->bairro) }}">
                    </div>
                </div>

                <div id="membro-outros" class="tab-pane form-control">
                    <div class="form-item">
                        <label for="data_batismo">{{ $common['fields']['baptism_date'] }}:</label>
                        <input type="date" name="data_batismo" id="data_batismo" value="{{ old('data_batismo', $membro->data_batismo) }}">
                    </div>
                    <div class="form-item">
                        <label for="denominacao_origem">{{ $common['fields']['origin_denomination'] }}:</label>
                        <input type="text" name="denominacao_origem" id="denominacao_origem" placeholder="{{ $common['placeholders']['origin_denomination'] ?? $common['fields']['origin_denomination'] }}" value="{{ old('denominacao_origem', $membro->denominacao_origem) }}">
                    </div>
                    <div class="form-item">
                        <label for="ministerio">{{ $common['fields']['ministry'] }}:</label>
                        <select name="ministerio" id="ministerio">
                            @foreach ($ministerios as $item)
                                <option value="{{ $item->id }}" @selected(old('ministerio', $membro->ministerio_id) == $item->id)>
                                    {{ $item->titulo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-item">
                        <label for="nome_paterno">{{ $common['fields']['father_name'] }}:</label>
                        <input type="text" name="nome_paterno" id="nome_paterno" placeholder="{{ $common['placeholders']['father_name'] ?? $common['fields']['father_name'] }}" value="{{ old('nome_paterno', $membro->nome_paterno) }}">
                    </div>
                    <div class="form-item">
                        <label for="nome_materno">{{ $common['fields']['mother_name'] }}:</label>
                        <input type="text" name="nome_materno" id="nome_materno" placeholder="{{ $common['placeholders']['mother_name'] ?? $common['fields']['mother_name'] }}" value="{{ old('nome_materno', $membro->nome_materno) }}">
                    </div>
                </div>
            </div>

            <div class="form-options center">
                <button class="btn" type="submit"><i class="bi bi-arrow-clockwise"></i> {{ $common['buttons']['update_member'] }}</button>
                <button type="button" onclick="window.history.back()" class="btn"><i class="bi bi-x-circle"></i> {{ $common['buttons']['cancel'] }}</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.tab-menu li').forEach(function (tab) {
            tab.addEventListener('click', function () {
                const target = tab.dataset.tab;
                tab.closest('.tabs').querySelectorAll('.tab-menu li').forEach(li => li.classList.remove('active'));
                tab.closest('.tabs').querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
                tab.classList.add('active');
                const pane = document.getElementById(target);
                if (pane) {
                    pane.classList.add('active');
                }
            });
        });
    });
</script>
@endpush
