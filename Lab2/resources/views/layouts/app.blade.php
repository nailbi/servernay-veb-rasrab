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
    {{-- ЛР11: сборка Vite — Vue-компонент с пуш-уведомлениями и laravel-echo --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    {{-- ШАПКА САЙТА: логотип + меню навигации --}}
    <header class="site-header">
        <div class="container header-inner">
            <a href="{{ route('home') }}" class="logo">Мой сайт</a>
            <nav class="nav">
                <a href="{{ route('home') }}" class="nav-link @if(request()->routeIs('home')) is-active @endif">Главная</a>
                <a href="{{ route('articles.index') }}" class="nav-link @if(request()->routeIs('articles.index', 'articles.show')) is-active @endif">Новости</a>

                {{-- Вкладка «Создать новость» — только для модератора (ArticlePolicy::create) --}}
                @can('create', App\Models\Article::class)
                    <a href="{{ route('articles.create') }}" class="nav-link @if(request()->routeIs('articles.create')) is-active @endif">Создать новость</a>
                @endcan

                {{-- Панель модерации комментариев — только для модератора (шлюз moderate) --}}
                @can('moderate')
                    <a href="{{ route('comments.moderation') }}" class="nav-link @if(request()->routeIs('comments.moderation')) is-active @endif">Модерация</a>
                @endcan
                <a href="{{ route('about') }}" class="nav-link @if(request()->routeIs('about')) is-active @endif">О нас</a>
                <a href="{{ route('contacts') }}" class="nav-link @if(request()->routeIs('contacts')) is-active @endif">Контакты</a>

                {{-- Гостю — ссылки на вход и регистрацию, авторизованному — имя и выход --}}
                @guest
                    <a href="{{ route('signin') }}" class="nav-link @if(request()->routeIs('signin*')) is-active @endif">Регистрация</a>
                    <a href="{{ route('login') }}" class="btn @if(request()->routeIs('login*')) btn-solid @endif">Войти</a>
                @endguest

                @auth
                    {{-- ЛР12: выпадающий список непрочитанных уведомлений и их количество --}}
                    @php($unread = auth()->user()->unreadNotifications)
                    <details class="notify">
                        <summary class="nav-link notify-toggle" title="Уведомления" aria-label="Уведомления: {{ $unread->count() }} непрочитанных">
                            <svg class="notify-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                            </svg>
                            @if ($unread->isNotEmpty())
                                <span class="notify-count">{{ $unread->count() }}</span>
                            @endif
                        </summary>
                        <div class="notify-menu">
                            @forelse ($unread as $notification)
                                <a href="{{ route('notifications.read', $notification->id) }}" class="notify-item">
                                    <span class="notify-title">Новая статья: {{ $notification->data['article_name'] }}</span>
                                    <span class="notify-time">{{ $notification->created_at->format('d.m.Y H:i') }}</span>
                                </a>
                            @empty
                                <p class="notify-empty">Новых уведомлений нет.</p>
                            @endforelse
                        </div>
                    </details>

                    <span class="nav-user">{{ auth()->user()->name }} · {{ auth()->user()->role?->name ?? 'без роли' }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="nav-logout">
                        @csrf
                        <button type="submit" class="btn">Выйти</button>
                    </form>
                @endauth
            </nav>
        </div>
    </header>

    {{-- ОСНОВНАЯ ЧАСТЬ: содержимое конкретной страницы --}}
    <main class="main-content">
        {{-- Flash-сообщение после создания/изменения/удаления --}}
        @if (session('success'))
            <div class="container">
                <div class="alert">{{ session('success') }}</div>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Точка монтирования Vue: всплывающее уведомление о новой статье --}}
    <div id="app"></div>

    {{-- ПОДВАЛ: ФИО и группа --}}
    <footer class="site-footer">
        <div class="container footer-inner">
            <span class="footer-author">Ведерников Никита Михайлович</span>
            <span class="footer-group">группа 251-3210</span>
        </div>
    </footer>
</body>
</html>
