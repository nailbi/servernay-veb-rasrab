@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <section class="hero">
        <div class="container">
            <p class="eyebrow">Серверная веб-разработка</p>
            <h1 class="hero-title">Добро пожаловать</h1>
            <p class="hero-lead">
                Учебный сайт на Laravel: маршрутизатор, шаблонизатор Blade и контроллер.
                Ниже — новости, загруженные контроллером из json-файла.
            </p>
            <div class="hero-actions">
                <a href="{{ route('about') }}" class="btn btn-solid">О нас</a>
                <a href="{{ route('contacts') }}" class="btn">Контакты</a>
            </div>
        </div>
    </section>

    <section class="page">
        <div class="container">
            <p class="label label-first">Новости</p>

            {{-- Вывод данных из json-файла, переданных методом index контроллера --}}
            @if (!empty($articles))
                <table class="articles">
                    <thead>
                        <tr>
                            <th>Дата</th>
                            <th>Изображение</th>
                            <th>Название</th>
                            <th>Краткое описание</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($articles as $id => $article)
                            <tr>
                                <td class="a-date">{{ $article['date'] }}</td>
                                <td>
                                    {{-- preview_image — ссылка на страницу galery --}}
                                    <a href="{{ route('galery', ['id' => $id]) }}" class="a-preview">
                                        <img src="{{ asset('images/' . $article['preview_image']) }}"
                                             alt="{{ $article['name'] }}">
                                    </a>
                                </td>
                                <td class="a-name">{{ $article['name'] }}</td>
                                <td class="a-desc">{{ $article['shortDesc'] ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="prose">Новостей пока нет.</p>
            @endif
        </div>
    </section>
@endsection
