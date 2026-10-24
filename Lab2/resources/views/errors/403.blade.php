@extends('layouts.app')

@section('title', 'Доступ запрещён')

@section('content')
    <section class="page">
        <div class="container">
            <p class="eyebrow">Ошибка 403</p>
            <h1 class="page-title">Доступ запрещён</h1>

            {{-- Текст приходит из Response::deny() политики или шлюза --}}
            <div class="prose">
                <p>{{ $message ?? 'У вас нет прав для этого действия.' }}</p>
            </div>

            <div class="form-actions">
                <a href="{{ url()->previous() }}" class="btn">← Назад</a>
                <a href="{{ route('home') }}" class="btn btn-solid">На главную</a>
            </div>
        </div>
    </section>
@endsection
