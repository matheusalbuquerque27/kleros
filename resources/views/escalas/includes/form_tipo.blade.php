@php
    $editing = isset($tipo);
    $action = $editing ? route('escalas.tipos.update', $tipo->id) : route('escalas.tipos.store');
    $titulo = $editing ? 'Editar tipo de escala' : 'Novo tipo de escala';
@endphp

<h1>{{ $titulo }}</h1>
<form action="{{ $action }}" method="post">
    @csrf
    @if($editing)
        @method('PUT')
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-control">
        <div class="form-item">
            <label for="nome">Nome do tipo:</label>
            <input type="text" name="nome" id="nome" value="{{ old('nome', $editing ? $tipo->nome : '') }}" placeholder="Ex: Louvor" required>
        </div>
        <div class="form-item">
            <label for="ativo">
                <input type="checkbox" name="ativo" id="ativo" value="1" {{ old('ativo', $editing ? $tipo->ativo : true) ? 'checked' : '' }}>
                Ativo
            </label>
        </div>
        <div class="form-options">
            <button type="submit" class="btn"><i class="bi bi-check-circle"></i> {{ $editing ? 'Atualizar tipo' : 'Salvar tipo' }}</button>
            <button type="button" class="btn" onclick="fecharJanelaModal()"><i class="bi bi-x-circle"></i> Cancelar</button>
        </div>
    </div>
</form>
