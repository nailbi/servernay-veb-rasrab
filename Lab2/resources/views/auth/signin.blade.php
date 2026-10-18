@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
    <section class="page">
        <div class="container">
            <p class="eyebrow">Регистрация</p>
            <h1 class="page-title">Создать аккаунт</h1>

            {{-- Форма отправляется методом POST на страницу signin --}}
            <form action="{{ route('signin.registration') }}" method="POST" class="form" novalidate>
                {{-- CSRF-токен для защиты от межсайтовой подделки запроса --}}
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">Имя</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           class="form-input @error('name') is-invalid @enderror" placeholder="Иван">
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

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
                           class="form-input @error('password') is-invalid @enderror" placeholder="Не менее 6 символов">
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-solid">Зарегистрироваться</button>

                <p class="form-note">Уже есть аккаунт? <a href="{{ route('login') }}">Войдите</a></p>
            </form>
        </div>
    </section>
@endsection
