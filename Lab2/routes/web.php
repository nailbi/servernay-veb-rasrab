<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
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

// Регистрация и вход — только для гостей (посредник guest).
Route::middleware('guest')->group(function () {
    // Регистрация: GET — показать форму, POST — сохранить пользователя в БД.
    Route::get('/signin', [AuthController::class, 'create'])->name('signin');
    Route::post('/signin', [AuthController::class, 'registration'])->name('signin.registration');

    // Вход: GET — показать форму, POST — аутентификация и выдача токена.
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
});

// Выход — только для авторизованных пользователей.
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum')
    ->name('logout');

// Новости из базы данных (модель Article).
// Создание, изменение и удаление — только для авторизованных (auth:sanctum).
// Регистрируются первыми, чтобы /articles/create не перехватывался маршрутом show.
Route::resource('articles', ArticleController::class)
    ->except(['index', 'show'])
    ->middleware('auth:sanctum');

// Просмотр списка и отдельной новости доступен всем.
Route::resource('articles', ArticleController::class)->only(['index', 'show']);
