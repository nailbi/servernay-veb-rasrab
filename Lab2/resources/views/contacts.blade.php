@extends('layouts.app')

@section('title', 'Контакты')

@section('content')
    <section class="page">
        <div class="container">
            <p class="eyebrow">Контакты</p>
            <h1 class="page-title">Связаться</h1>

            <p class="prose">Свяжитесь с нами любым удобным способом.</p>

            {{-- Динамический вывод массива данных, переданного из маршрутизатора --}}
            @if (!empty($contacts))
                <ul class="contacts">
                    @foreach ($contacts as $contact)
                        <li class="contacts-item">
                            <span class="c-label">{{ $contact['label'] }}</span>
                            <span class="c-value">{{ $contact['value'] }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="prose">Контактные данные пока не заполнены.</p>
            @endif
        </div>
    </section>
@endsection
