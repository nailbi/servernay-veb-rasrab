<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Мой сайт') — Серверная веб-разработка</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    {{-- ШАПКА САЙТА: логотип + меню навигации --}}
    <header class="site-header">
        <div class="container header-inner">
            <a href="{{ route('home') }}" class="logo">Мой сайт</a>
            <nav class="nav">
                <a href="{{ route('home') }}" class="nav-link @if(request()->routeIs('home')) is-active @endif">Главная</a>
                <a href="{{ route('about') }}" class="nav-link @if(request()->routeIs('about')) is-active @endif">О нас</a>
                <a href="{{ route('contacts') }}" class="btn @if(request()->routeIs('contacts')) btn-solid @endif">Контакты</a>
            </nav>
        </div>
    </header>

    {{-- ОСНОВНАЯ ЧАСТЬ: содержимое конкретной страницы --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- ПОДВАЛ: ФИО и группа --}}
    <footer class="site-footer">
        <div class="container footer-inner">
            <span class="footer-author">Ведерников Никита Михайлович</span>
            <span class="footer-group">группа 251-3210</span>
        </div>
    </footer>
</body>
</html>
