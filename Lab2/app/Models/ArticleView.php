<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Модель просмотра статьи — работает с таблицей article_views (ЛР14).
 * Создана командой: php artisan make:model ArticleView -m
 */
class ArticleView extends Model
{
    /**
     * Поля, разрешённые для массового заполнения.
     */
    protected $fillable = [
        'url',
        'article_id',
        'user_id',
        'ip',
    ];

    /**
     * Просмотренная статья.
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Локальная область: просмотры за сегодня — ArticleView::today().
     */
    public function scopeToday(Builder $query): void
    {
        $query->whereDate('created_at', today());
    }
}
