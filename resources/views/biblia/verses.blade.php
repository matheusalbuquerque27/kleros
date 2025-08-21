@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')
<div class="container">
    <h1>Bíblia Sagrada - NVI</h1>
    <div class="biblia-container">
        <h2><i class="bi bi-book"></i> {{ $book->name }} - Capítulo {{ $chapter }}</h2>
        @foreach ($versiculos as $v)
            <p>
                <strong>{{ $v->verse }}</strong><span class="each-verse" id="{{$v->id}}"> {{ $v->text }}</span>
            </p>
        @endforeach

        <br>
        <a href="{{ route('biblia.chapters', $book->id) }}" class="btn-voltar" >⬅ Voltar para capítulos</a>
    </div>
</div>
@endsection

@push('scripts')

<script>
    $(document).on('click', '.each-verse', function() {
        alert(this.id);
    });
</script>

@endpush