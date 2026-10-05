@extends('layouts.app')

@section('title', 'Галерея')

@section('content')
    <section class="page">
        <div class="container">
            <p class="eyebrow">Галерея · {{ $article['date'] }}</p>
            <h1 class="page-title">{{ $article['name'] }}</h1>

            {{-- Полноразмерное изображение статьи --}}
            <figure class="gallery-figure">
                <img src="{{ asset('images/' . $article['full_image']) }}" alt="{{ $article['name'] }}">
            </figure>

            <p class="prose">{{ $article['desc'] }}</p>

            <a href="{{ route('home') }}" class="btn">← К новостям</a>
        </div>
    </section>
@endsection
