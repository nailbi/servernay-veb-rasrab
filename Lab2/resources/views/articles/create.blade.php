@extends('layouts.app')

@section('title', 'Новая новость')

@section('content')
    <section class="page">
        <div class="container">
            <p class="eyebrow">Новости</p>
            <h1 class="page-title">Новая новость</h1>

            <form action="{{ route('articles.store') }}" method="POST" class="form form-wide" novalidate>
                @include('articles.partials.form')

                <div class="form-actions">
                    <button type="submit" class="btn btn-solid">Создать</button>
                    <a href="{{ route('articles.index') }}" class="btn">Отмена</a>
                </div>
            </form>
        </div>
    </section>
@endsection
