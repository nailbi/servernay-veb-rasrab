<?php

namespace App\Models;

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
     * У статьи много комментариев (один ко многим).
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
