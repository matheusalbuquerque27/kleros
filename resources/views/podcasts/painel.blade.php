@extends('layouts.main')

@section('title', 'Podcasts')

@section('content')

<div class="container">
    <h1>Podcasts</h1>
    <div class="info">
        <h3 id="teologia">Teologia</h3>
        <div class="noticias-container">
            <div class="noticias-grid">
                @foreach ($podcasts['btcast'] as $item)
                    <div class="noticia-card">
                         {{-- Se o enclosure for áudio --}}
                        @if(Str::endsWith($item['audio'], ['.mp3', '.ogg', '.wav']))
                            <audio controls style="width: 100%; margin-top: 1rem;">
                                <source src="{{$item['audio']}}" type="audio/mpeg">
                                Seu navegador não suporta o elemento de áudio.
                            </audio>
                        @endif
                        <a href="{{ $item['link'] }}" target="_blank" class="noticia-title">
                            {{ $item['title'] }}
                        </a>
                        <div class="noticia-date">
                            {{ $item['date'] }}
                        </div>
                        <div class="noticia-desc">
                            {!! substr(strip_tags($item['description'] ?? ''), 0, 200) !!}...
                        </div>
                        <a href="{{ $item['link'] }}" target="_blank" class="noticia-link">
                            Ler mais
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        <h3>Sociedade</h3>
        <h3>Ciência e Fé</h3>
        <div class="noticias-container">
            <div class="noticias-grid">
                @foreach ($podcasts['btcast'] as $item)
                    <div class="noticia-card">
                         {{-- Se o enclosure for áudio --}}
                        @if(Str::endsWith($item['audio'], ['.mp3', '.ogg', '.wav']))
                            <audio controls style="width: 100%; margin-top: 1rem;">
                                <source src="{{$item['audio']}}" type="audio/mpeg">
                                Seu navegador não suporta o elemento de áudio.
                            </audio>
                        @endif
                        <a href="{{ $item['link'] }}" target="_blank" class="noticia-title">
                            {{ $item['title'] }}
                        </a>
                        <div class="noticia-date">
                            {{ $item['date'] }}
                        </div>
                        <div class="noticia-desc">
                            {!! substr(strip_tags($item['description'] ?? ''), 0, 200) !!}...
                        </div>
                        <a href="{{ $item['link'] }}" target="_blank" class="noticia-link">
                            Ler mais
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        <h3>Diversos</h3>
    </div>
</div>
<div class="podcast-menu-bar">
    <ul class="podcast-menu">
        <li><a href="#teologia">BTCast</a></li>
        <li><a href="#sociedade">JesusCopy</a></li>
        <li><a href="#ciencia">Ciência e Fé</a></li>
        <li><a href="#diversos">Diversos</a></li>
    </ul>
</div>
@endsection