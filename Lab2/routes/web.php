<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web-маршруты (ЛР1: Blade + Route)
|--------------------------------------------------------------------------
| Маршрутизатор отвечает за рендеринг страниц сайта.
*/

// Главная — приветственная страница (в будущем — список новостей).
Route::get('/', function () {
    return view('home');
})->name('home');

// «О нас» — текстовая страница.
Route::get('/about', function () {
    return view('about');
})->name('about');

// «Контакты» — данные формируются в виде массива и передаются
// в шаблон методом view() для динамического вывода.
Route::get('/contacts', function () {
    $contacts = [
        ['label' => 'Адрес',       'value' => 'г. Москва, ул. Примерная, д. 1, оф. 42'],
        ['label' => 'Телефон',     'value' => '+7 (900) 123-45-67'],
        ['label' => 'E-mail',      'value' => 'info@example.com'],
        ['label' => 'Telegram',    'value' => '@example_support'],
        ['label' => 'Часы работы', 'value' => 'Пн–Пт, 9:00–18:00'],
    ];

    return view('contacts', ['contacts' => $contacts]);
})->name('contacts');
