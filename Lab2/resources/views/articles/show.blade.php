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

            {{-- КОММЕНТАРИИ: только одобренные модератором --}}
            <div class="comments" id="comments">
                <h2 class="comments-title">Комментарии ({{ $comments->count() }})</h2>

                @forelse ($comments as $comment)
                    <div class="comment">
                        <p class="comment-meta">
                            <strong>{{ $comment->user->name }}</strong>
                            · {{ $comment->created_at->format('d.m.Y H:i') }}
                        </p>
                        <p class="comment-text">{!! nl2br(e($comment->text)) !!}</p>
                    </div>
                @empty
                    <p class="comment-empty">Комментариев пока нет.</p>
                @endforelse

                @auth
                    <form action="{{ route('comments.store', $article) }}" method="POST" class="form form-wide comment-form" novalidate>
                        @csrf
                        <div class="form-group">
                            <label for="text" class="form-label">Ваш комментарий</label>
                            <textarea id="text" name="text" rows="4"
                                      class="form-input @error('text') is-invalid @enderror"
                                      placeholder="Комментарий появится после проверки модератором">{{ old('text') }}</textarea>
                            @error('text')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-solid">Отправить</button>
                        </div>
                    </form>
                @else
                    <p class="comment-empty">
                        <a href="{{ route('login') }}">Войдите</a>, чтобы оставить комментарий.
                    </p>
                @endauth
            </div>
        </div>
    </section>
@endsection
