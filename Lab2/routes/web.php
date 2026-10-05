<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

// Главная — список статей из json-файла (метод index контроллера).
Route::get('/', [MainController::class, 'index'])->name('home');

// Галерея — полноразмерное изображение статьи (full_image).
Route::get('/galery/{id}', [MainController::class, 'galery'])
    ->whereNumber('id')
    ->name('galery');

// «О нас» — текстовая страница.
Route::get('/about', function () {
    return view('about');
})->name('about');

// «Контакты» — данные формируются в виде массива и передаются
// в шаблон методом view() для динамического вывода.
Route::get('/contacts', function () {
    $contacts = [
        ['label' => 'Адрес',       'value' => 'г. Москва, ул. Прянишникова, д. 1, оф. 1'],
        ['label' => 'Телефон',     'value' => '+7 (900) 818-23-80'],
        ['label' => 'E-mail',      'value' => 'info@example.com'],
        ['label' => 'Telegram',    'value' => '@example_support'],
        ['label' => 'Часы работы', 'value' => 'Пн–Пт, 9:00–18:00'],
    ];

    return view('contacts', ['contacts' => $contacts]);
})->name('contacts');
