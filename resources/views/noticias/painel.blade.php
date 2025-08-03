@extends('layouts.main')

@section('title', 'Notícias Cristãs')

@section('content')

<div class="container">
    <h1>Notícias Cristãs</h1>
    <div class="info">
        <h3>Cristianismo pelo mundo</h3>
        <div class="noticias-container">
            <div class="noticias-grid">
                @foreach ($noticias['guiame'] as $noticia)
                    <div class="noticia-card">
                        @if (!empty($noticia['image']))
                            <img src="{{ $noticia['image'] }}" alt="" class="noticia-img">
                        @endif
                        <a href="{{ $noticia['link'] }}" target="_blank" class="noticia-title">
                            {{ $noticia['title'] }}
                        </a>
                        <div class="noticia-date">
                            {{ $noticia['date'] }}
                        </div>
                        <div class="noticia-desc">
                            {!! substr(strip_tags($noticia['description'] ?? ''), 0, 200) !!}...
                        </div>
                        <a href="{{ $noticia['link'] }}" target="_blank" class="noticia-link">
                            Ler mais
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        <h3>Atualidades</h3>
        <div class="noticias-container">
            <div class="noticias-grid">
                @foreach ($noticias['gospel+'] as $noticia)
                    <div class="noticia-card">
                        @if (!empty($noticia['image']))
                            <img src="{{ $noticia['image'] }}" alt="" class="noticia-img">
                        @endif
                        <a href="{{ $noticia['link'] }}" target="_blank" class="noticia-title">
                            {{ $noticia['title'] }}
                        </a>
                        <div class="noticia-date">
                            {{ $noticia['date'] }}
                        </div>
                        <div class="noticia-desc">
                            {!! substr(strip_tags($noticia['description'] ?? ''), 0, 200) !!}...
                        </div>
                        <a href="{{ $noticia['link'] }}" target="_blank" class="noticia-link">
                            Ler mais
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection