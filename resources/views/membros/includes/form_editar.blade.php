
<h1>Editar membro</h1>
<div class="info">
    <form action="{{ route('membros.atualizar', $membro->id) }}" method="post">
        @csrf
        @method('PUT') {{-- Método PUT para atualização --}}

        {{-- Exibe erros gerais, se houver --}}
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
                <li class="active" data-tab="membro-dados"><i class="bi bi-person-badge"></i> Dados pessoais</li>
                <li data-tab="membro-endereco"><i class="bi bi-geo-alt"></i> Endereço</li>
                <li data-tab="membro-outros"><i class="bi bi-people"></i> Outros dados</li>
            </ul>

            <div class="tab-content card">
                <div id="membro-dados" class="tab-pane form-control active">
                    <div class="form-item">
                        <label for="nome">Nome: </label>
                        <input type="text" name="nome" id="nome" placeholder="Nome completo" value="{{ old('nome', $membro->nome) }}" required>
                        {{-- Exibe erro específico para o campo 'nome' --}}
                        @error('nome')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-item">
                        <label for="rg">RG: </label>
                        <input type="text" name="rg" id="rg" placeholder="RG" value="{{ old('rg', $membro->rg) }}">
                    </div>
                    <div class="form-item">
                        <label for="cpf">CPF: </label>
                        <input type="text" name="cpf" id="cpf" placeholder="CPF" value="{{ old('cpf', $membro->cpf) }}">
                    </div>
                    <div class="form-item">
                        <label for="data_nascimento">Data de nascimento: </label>
                        <input type="date" name="data_nascimento" id="data_nascimento" value="{{ old('data_nascimento', $membro->data_nascimento) }}" required>
                    </div>
                    <div class="form-item">
                        <label for="telefone">Telefone: </label>
                        <input type="text" name="telefone" id="telefone" placeholder="Telefone" value="{{ old('telefone', $membro->telefone) }}" required>
                    </div>
                    <div class="form-item">
                        <label for="estado_civil">Estado civil: </label>
                        <select name="estado_civil" id="estado_civil">
                            @foreach ($estado_civil as $item)
                                <option value="{{ $item->id }}" @selected(old('estado_civil', $membro->estado_civ_id) == $item->id)>
                                    {{ $item->titulo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-item">
                        <label for="escolaridade">Escolaridade: </label>
                        <select name="escolaridade" id="escolaridade">
                            @foreach ($escolaridade as $item)
                                <option value="{{ $item->id }}" @selected(old('escolaridade', $membro->escolaridade_id) == $item->id)>
                                    {{ $item->titulo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-item">
                        <label for="profissao">Profissão: </label>
                        <input type="text" name="profissao" id="profissao" placeholder="Profissão" value="{{ old('profissao', $membro->profissao) }}">
                    </div>
                </div>

                <div id="membro-endereco" class="tab-pane form-control">
                    <div class="form-item">
                        <label for="endereco">Endereço: </label>
                        <input type="text" name="endereco" id="endereco" placeholder="Endereço" value="{{ old('endereco', $membro->endereco) }}">
                    </div>
                    <div class="form-item">
                        <label for="numero">Número: </label>
                        <input type="text" name="numero" id="numero" placeholder="Número" value="{{ old('numero', $membro->numero) }}">
                    </div>
                    <div class="form-item">
                        <label for="bairro">Bairro: </label>
                        <input type="text" name="bairro" id="bairro" placeholder="Bairro" value="{{ old('bairro', $membro->bairro) }}">
                    </div>
                </div>

                <div id="membro-outros" class="tab-pane form-control">
                    <div class="form-item">
                        <label for="data_batismo">Data de Batismo: </label>
                        <input type="date" name="data_batismo" id="data_batismo" value="{{ old('data_batismo', $membro->data_batismo) }}">
                    </div>
                    <div class="form-item">
                        <label for="denominacao_origem">Denominação de Origem: </label>
                        <input type="text" name="denominacao_origem" id="denominacao_origem" placeholder="Denominação de origem" value="{{ old('denominacao_origem', $membro->denominacao_origem) }}">
                    </div>
                    <div class="form-item">
                        <label for="ministerio">Ministério: </label>
                        <select name="ministerio" id="ministerio">
                            @foreach ($ministerios as $item)
                                <option value="{{ $item->id }}" @selected(old('ministerio', $membro->ministerio_id) == $item->id)>
                                    {{ $item->titulo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-item">
                        <label for="nome_paterno">Nome paterno: </label>
                        <input type="text" name="nome_paterno" id="nome_paterno" placeholder="Nome paterno" value="{{ old('nome_paterno', $membro->nome_paterno) }}">
                    </div>
                    <div class="form-item">
                        <label for="nome_materno">Nome materno: </label>
                        <input type="text" name="nome_materno" id="nome_materno" placeholder="Nome materno" value="{{ old('nome_materno', $membro->nome_materno) }}">
                    </div>
                </div>
            </div>

            <div class="form-options center">
                <button class="btn" type="submit"><i class="bi bi-arrow-clockwise"></i> Atualizar</button>
                <button type="button" onclick="window.history.back()" class="btn"><i class="bi bi-x-circle"></i> Cancelar</button>
            </div>
        </div>
    </form>
</div>
