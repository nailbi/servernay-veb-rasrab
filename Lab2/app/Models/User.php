<?php

namespace App\Models;

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
}
