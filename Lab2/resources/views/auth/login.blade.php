@extends('layouts.app')

@section('title', 'Вход')

@section('content')
    <section class="page">
        <div class="container">
            <p class="eyebrow">Авторизация</p>
            <h1 class="page-title">Войти в аккаунт</h1>

            {{-- Форма отправляется методом POST на страницу login --}}
            <form action="{{ route('login.authenticate') }}" method="POST" class="form" novalidate>
                {{-- CSRF-токен для защиты от межсайтовой подделки запроса --}}
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="form-input @error('email') is-invalid @enderror" placeholder="ivan@example.com">
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Пароль</label>
                    <input type="password" id="password" name="password"
                           class="form-input @error('password') is-invalid @enderror">
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <label class="form-check">
                    <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                    Запомнить меня
                </label>

                <button type="submit" class="btn btn-solid">Войти</button>

                <p class="form-note">Нет аккаунта? <a href="{{ route('signin') }}">Зарегистрируйтесь</a></p>
            </form>
        </div>
    </section>
@endsection
