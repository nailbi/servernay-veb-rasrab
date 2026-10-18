<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создание таблицы пользователей.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');                   // имя
            $table->string('email')->unique();        // e-mail (логин)
            $table->string('password');               // хэш пароля
            $table->rememberToken();                  // токен «запомнить меня»
            $table->timestamps();
        });
    }

    /**
     * Удаление таблицы пользователей.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
