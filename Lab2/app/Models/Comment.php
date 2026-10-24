<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Модель комментария — работает с таблицей comments.
 */
class Comment extends Model
{
    /**
     * Поля, разрешённые для массового заполнения.
     * is_approved сюда не входит: одобрить может только модератор.
     */
    protected $fillable = [
        'text',
        'user_id',
    ];

    /**
     * Приведение типов: is_approved — true/false.
     */
    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
        ];
    }

    /**
     * Комментарий относится к одной статье.
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Автор комментария.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Локальная область: только прошедшие модерацию — Comment::approved().
     */
    public function scopeApproved(Builder $query): void
    {
        $query->where('is_approved', true);
    }

    /**
     * Локальная область: ожидающие модерации — Comment::pending().
     */
    public function scopePending(Builder $query): void
    {
        $query->where('is_approved', false);
    }
}
