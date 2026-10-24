@extends('layouts.app')

@section('title', $article->name)

@section('content')
    <section class="page">
        <div class="container">
            <p class="eyebrow">{{ $article->date->format('d.m.Y') }}</p>
            <h1 class="page-title">{{ $article->name }}</h1>

            <figure class="gallery-figure">
                <img src="{{ asset('images/' . $article->full_image) }}" alt="{{ $article->name }}">
            </figure>

            <div class="prose">
                <p><strong>{{ $article->short_desc }}</strong></p>
                {{-- nl2br(e()) — экранируем текст и сохраняем переносы строк --}}
                <p>{!! nl2br(e($article->desc)) !!}</p>
            </div>

            <div class="form-actions">
                <a href="{{ route('articles.index') }}" class="btn">← К списку</a>
                {{-- Кнопки видит только тот, кому политика разрешает update (модератор) --}}
                @can('update', $article)
                    @include('articles.partials.actions', ['article' => $article])
                @endcan
            </div>
        </div>
    </section>
@endsection
