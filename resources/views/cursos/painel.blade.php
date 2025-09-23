@extends('layouts.main')

@section('title', 'Cursos | ' . $appName)

@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

@section('content')
<div class="container">
    <h1>Cursos</h1>
    <div class="info">
        <h3 id="cursos">Trilhas de aprendizagem</h3>
        <p class="noticia-date">Explore os cursos disponíveis, acompanhe os módulos e compartilhe recursos com sua equipe.</p>
        <div class="noticias-container">
            <div class="noticias-grid">
                @forelse ($cursos as $curso)
                    @php
                        $thumbnail = $curso->icone
                            ? Storage::url($curso->icone)
                            : asset('storage/images/podcast.png');
                    @endphp
                    <div class="noticia-card curso-card">
                        <img src="{{ $thumbnail }}" alt="Capa do curso {{ $curso->titulo }}" class="noticia-img">
                        <h4 class="noticia-title">{{ $curso->titulo }}</h4>
                        <div class="noticia-date">
                            {{ $curso->publico ? 'Curso público' : 'Curso restrito à congregação' }}
                        </div>
                        <div class="noticia-desc">
                            {{ Str::limit($curso->descricao ?? 'Curso sem descrição cadastrada.', 180) }}
                        </div>

                        @if ($curso->modulos->isNotEmpty())
                            <div class="curso-modulos-wrapper">
                                <div class="curso-modulos-header">
                                    <span class="curso-modulos-title">Módulos ativos ({{ $curso->modulos->count() }})</span>
                                </div>
                                <div class="curso-modulos">
                                    @foreach ($curso->modulos as $modulo)
                                        <div class="curso-modulo-item">
                                            <div class="curso-modulo-nome">
                                                <span>{{ $modulo->nome }}</span>
                                                <span class="curso-tag {{ $modulo->publico ? 'curso-tag-publico' : 'curso-tag-restrito' }}">
                                                    {{ $modulo->publico ? 'Público' : 'Restrito' }}
                                                </span>
                                            </div>
                                            @if ($modulo->descricao)
                                                <p class="curso-modulo-desc">{{ Str::limit($modulo->descricao, 140) }}</p>
                                            @endif
                                            @if ($modulo->url)
                                                <div class="curso-modulo-footer">
                                                    <a href="{{ $modulo->url }}" target="_blank" rel="noopener" class="curso-modulo-link">
                                                        Acessar conteúdo
                                                        <i class="bi bi-box-arrow-up-right"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <span class="curso-sem-modulos">Nenhum módulo ativo vinculado a este curso.</span>
                        @endif
                    </div>
                @empty
                    <div class="noticia-card">
                        <h4 class="noticia-title">Nenhum curso disponível</h4>
                        <p class="curso-sem-modulos">Cadastre cursos na área administrativa para começar a disponibilizar conteúdo.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@once
<style>
    .curso-card {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        background: linear-gradient(160deg, rgba(255, 255, 255, 0.98), rgba(247, 249, 253, 0.95));
        border: 1px solid rgba(10, 25, 41, 0.08);
        box-shadow: 0 12px 28px -20px rgba(10, 25, 41, 0.45);
    }

    .curso-card .noticia-desc {
        margin: 0;
        padding: 0.75rem 0.85rem;
        border-radius: 8px;
        background: rgba(103, 123, 150, 0.08);
        color: #1d2230;
        line-height: 1.65;
        font-size: 0.97rem;
    }

    .curso-modulos-wrapper {
        margin-top: 0.25rem;
        border-top: 1px solid rgba(10, 25, 41, 0.08);
        padding-top: 0.85rem;
    }

    .curso-modulos-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
        color: rgba(10, 25, 41, 0.75);
    }

    .curso-modulos-title {
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .curso-modulos {
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }

    .curso-modulo-item {
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
        padding: 1rem 1.1rem 1.15rem 1.65rem;
        background: rgba(255, 255, 255, 0.92);
        border-radius: 10px;
        border: 1px solid rgba(10, 25, 41, 0.06);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.65);
    }

    .curso-modulo-item::before {
        content: '';
        position: absolute;
        left: 0.85rem;
        top: 1.35rem;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: rgba(10, 25, 41, 0.4);
    }

    .curso-modulo-item:hover {
        border-color: rgba(244, 73, 22, 0.2);
        box-shadow: 0 8px 20px -18px rgba(10, 25, 41, 0.6);
    }

    .curso-modulo-nome {
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: rgba(10, 25, 41, 0.9);
    }

    .curso-tag {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        padding: 0.15rem 0.6rem;
        border-radius: 999px;
        background: var(--secondary-color);
        color: var(--secondary-contrast);
        box-shadow: 0 6px 12px -8px rgba(10, 25, 41, 0.6);
    }

    .curso-tag-publico {
        background: var(--primary-color);
        color: var(--primary-contrast);
    }

    .curso-tag-restrito {
        background: var(--secondary-color);
        color: var(--secondary-contrast);
    }

    .curso-modulo-desc {
        margin: 0;
        color: rgba(45, 50, 62, 0.92);
        font-size: 0.92rem;
        line-height: 1.6;
    }

    .curso-modulo-footer {
        margin-top: auto;
        display: flex;
        justify-content: flex-start;
        padding-top: 0.6rem;
    }

    .curso-modulo-link {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--terciary-color);
        transition: transform 0.2s ease, color 0.2s ease;
        text-decoration: none;
    }

    .curso-modulo-link:hover {
        color: var(--secondary-color);
        transform: translateY(-1px);
    }

    .curso-modulo-link .bi {
        font-size: 0.95rem;
    }

    .curso-sem-modulos {
        display: block;
        margin-top: 0.75rem;
        color: rgba(45, 50, 62, 0.8);
        font-size: 0.9rem;
        line-height: 1.6;
    }
</style>
@endonce
