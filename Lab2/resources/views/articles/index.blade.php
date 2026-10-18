@extends('layouts.app')

@section('title', 'Новости')

@section('content')
    <section class="page">
        <div class="container">
            <p class="eyebrow">Новости</p>
            <div class="page-head">
                <h1 class="page-title">Новости из базы данных</h1>
                @auth
                    <a href="{{ route('articles.create') }}" class="btn btn-solid">+ Добавить новость</a>
                @endauth
            </div>

            {{-- Пагинатор Article, переданный из ArticleController --}}
            @if ($articles->isNotEmpty())
                <table class="articles">
                    <thead>
                        <tr>
                            <th>Дата</th>
                            <th>Изображение</th>
                            <th>Название</th>
                            <th>Краткое описание</th>
                            @auth
                                <th>Действия</th>
                            @endauth
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($articles as $article)
                            <tr>
                                <td class="a-date">{{ $article->date->format('d.m.Y') }}</td>
                                <td>
                                    <a href="{{ route('articles.show', $article) }}" class="a-preview">
                                        <img src="{{ asset('images/' . $article->preview_image) }}"
                                             alt="{{ $article->name }}">
                                    </a>
                                </td>
                                <td class="a-name">
                                    <a href="{{ route('articles.show', $article) }}">{{ $article->name }}</a>
                                </td>
                                <td class="a-desc">{{ $article->short_desc }}</td>
                                @auth
                                    <td>
                                        @include('articles.partials.actions', ['article' => $article])
                                    </td>
                                @endauth
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Ссылки пагинации (собственный шаблон partials/pagination) --}}
                {{ $articles->links('partials.pagination') }}
            @else
                <p class="prose">Новостей пока нет. Выполните <code>php artisan migrate --seed</code>
                    @auth или <a href="{{ route('articles.create') }}">добавьте первую</a>@endauth.</p>
            @endif
        </div>
    </section>
@endsection
