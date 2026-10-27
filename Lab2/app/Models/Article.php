<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Модель новости — работает с таблицей articles.
 */
class Article extends Model
{
    use HasFactory;

    /**
     * Поля, разрешённые для массового заполнения.
     */
    protected $fillable = [
        'date',
        'name',
        'preview_image',
        'full_image',
        'short_desc',
        'desc',
    ];

    /**
     * Приведение типов: date — объект Carbon.
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    /**
     * Даты в JSON (API, ЛР15) — в часовом поясе приложения со смещением.
     * По умолчанию Laravel переводит их в UTC, и 27.10 превращается
     * в 2026-10-26T21:00:00Z.
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format(DateTimeInterface::ATOM);
    }

    /**
     * У статьи много комментариев (один ко многим).
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
