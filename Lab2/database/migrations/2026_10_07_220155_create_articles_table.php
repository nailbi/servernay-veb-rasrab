<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создание таблицы новостей.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->date('date');                     // дата публикации
            $table->string('name');                   // название
            $table->string('preview_image')->nullable(); // превью
            $table->string('full_image')->nullable();    // полное изображение
            $table->string('short_desc')->nullable(); // краткое описание
            $table->text('desc')->nullable();         // полный текст
            $table->timestamps();
        });
    }

    /**
     * Удаление таблицы новостей.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
