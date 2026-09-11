@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <section class="hero">
        <div class="container">
            <p class="eyebrow">Серверная веб-разработка</p>
            <h1 class="hero-title">Добро пожаловать</h1>
            <p class="hero-lead">
                Учебный сайт на Laravel: отработка маршрутизатора и шаблонизатора Blade.
                В основе — единый макет и раздельные страницы.
            </p>
            <div class="hero-actions">
                <a href="{{ route('about') }}" class="btn btn-solid">О нас</a>
                <a href="{{ route('contacts') }}" class="btn">Контакты</a>
            </div>
        </div>
    </section>

    <section class="page">
        <div class="container">
            <p class="label">Скоро</p>
            <p class="prose">Здесь появится список новостей.</p>
        </div>
    </section>
@endsection
