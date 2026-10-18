@extends('layouts.app')

@section('title', 'Редактирование новости')

@section('content')
    <section class="page">
        <div class="container">
            <p class="eyebrow">Новости</p>
            <h1 class="page-title">Редактирование новости</h1>

            <form action="{{ route('articles.update', $article) }}" method="POST" class="form form-wide" novalidate>
                {{-- Обновление выполняется методом PUT --}}
                @method('PUT')
                @include('articles.partials.form')

                <div class="form-actions">
                    <button type="submit" class="btn btn-solid">Сохранить</button>
                    <a href="{{ route('articles.show', $article) }}" class="btn">Отмена</a>
                </div>
            </form>
        </div>
    </section>
@endsection
