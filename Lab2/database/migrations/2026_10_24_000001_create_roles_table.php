<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создание таблицы ролей (модератор, читатель).
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');                   // название для людей: «Модератор»
            $table->string('slug')->unique();         // код для проверок: moderator / reader
            $table->timestamps();
        });
    }

    /**
     * Удаление таблицы ролей.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
