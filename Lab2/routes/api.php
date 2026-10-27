<?php

use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;

/*
| REST API (ЛР15). Все адреса начинаются с /api, ответы — JSON.
| Защищённые маршруты требуют заголовок Authorization: Bearer <token>,
| токен выдаёт POST /api/login.
| Имена маршрутов с префиксом api., чтобы не перекрывать имена сайта
| (route('articles.show') по-прежнему ведёт на страницу сайта).
*/

Route::name('api.')->group(function () {
    // Регистрация и вход — для всех.
    Route::post('/register', [AuthController::class, 'registration'])->name('register');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login');

    // Список и просмотр новостей — для всех. Просмотр считается в статистике (ЛР14).
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/{article}', [ArticleController::class, 'show'])
        ->whereNumber('article')
        ->middleware('track.view')
        ->name('articles.show');

    // Только с токеном Sanctum. Права на статьи проверяет ArticlePolicy.
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'user'])->name('user');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::apiResource('articles', ArticleController::class)->only(['store', 'update', 'destroy']);
    });
});
