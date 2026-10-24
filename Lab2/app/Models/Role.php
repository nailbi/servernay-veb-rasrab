<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Модель роли — работает с таблицей roles.
 */
class Role extends Model
{
    /**
     * Коды ролей, используемые в проверках доступа.
     */
    public const MODERATOR = 'moderator';
    public const READER    = 'reader';

    /**
     * Поля, разрешённые для массового заполнения.
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Одна роль — много пользователей (один ко многим).
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
