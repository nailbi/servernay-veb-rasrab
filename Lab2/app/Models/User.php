<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Модель пользователя — работает с таблицей users.
 * HasApiTokens добавляет работу с токенами Sanctum (createToken, tokens).
 */
class User extends Authenticatable
{
    use HasApiTokens;

    /**
     * Поля, разрешённые для массового заполнения.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * Поля, скрытые при сериализации.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Приведение типов: пароль автоматически хэшируется при присвоении.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Пользователь принадлежит одной роли (обратная сторона Role::users).
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Комментарии, написанные пользователем.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Является ли пользователь модератором.
     */
    public function isModerator(): bool
    {
        return $this->role?->slug === Role::MODERATOR;
    }
}
