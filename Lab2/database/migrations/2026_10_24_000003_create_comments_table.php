<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создание таблицы комментариев к новостям.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            // cascadeOnDelete — при удалении статьи или пользователя удаляются и его комментарии.
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('text');                     // текст комментария
            $table->timestamps();
        });
    }

    /**
     * Удаление таблицы комментариев.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
