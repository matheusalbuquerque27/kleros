@extends('layouts.kleros')

@section('title', 'Check-In - Cadastre sua denominação')

@section('content')

@if($errors->all())
<div class="msg">
    <div class="error">
        <ul>
            {{$errors->first()}}
        </ul>
    </div>
</div>
@endif

<div class="container">
    <h1>Registre sua denominação</h1>
    <form action="{{ route('denominacoes.store') }}" method="post">
        @csrf
        <div class="form-control">
            <div class="form-item">
                <label for="nome">Nome: </label>
                <input type="text" name="nome" id="nome" placeholder="Nome da igreja" required>
            </div>
            <div class="form-item">
                <label for="base_doutrinaria">Base doutrinária: </label>
                <select name="base_doutrinaria" id="base_doutrinaria" required>
                    <option value="">Tradição/Confissão: </option>
                    @foreach($bases_doutrinarias as $base)
                        <option value="{{ $base->id }}">{{ $base->nome }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-item">
                <label for="ministerios_eclesiasticos">Quais os ministérios/cargos eclesiásticos de sua igreja?</label>
                <input type="text" id="ministerio_input" placeholder="Digite o ministério e pressione ENTER. Ex: Pastor, Bispo, Presbítero">
            </div>

            <div class="form-item">
                <label for=""><i>* Clique no item para excluir</i></label>
                <div class="square"><div id="tags_container"></div></div>
                <input type="hidden" name="ministerios_eclesiasticos" id="ministerios_eclesiasticos">
            </div>
            
            <div class="form-options">
                <button class="btn" type="submit"><i class="bi bi-plus-circle"></i> Próximos passos</button>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')

<script>
    const input = document.getElementById('ministerio_input');
    const container = document.getElementById('tags_container');
    const hidden = document.getElementById('ministerios_eclesiasticos');
    let tags = [];

    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && input.value.trim() !== '') {
            e.preventDefault();
            tags.push(input.value.trim());
            renderTags();
            input.value = '';
            hidden.value = JSON.stringify(tags);
        }
    });

    function renderTags() {
        container.innerHTML = '';
        tags.forEach((tag, idx) => {
            const span = document.createElement('span');
            span.textContent = tag;
            span.className = 'tag';
            span.style.marginRight = '5px';
            // Remover tag ao clicar
            span.onclick = () => {
                tags.splice(idx, 1);
                renderTags();
                hidden.value = JSON.stringify(tags);
            };
            container.appendChild(span);
        });
    }
</script>

@endpush