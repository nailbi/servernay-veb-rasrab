<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Сохранение в таблице users модератора и тестового читателя.
     * Пароль хэшируется автоматически (cast 'hashed' в модели User).
     */
    public function run(): void
    {
        User::updateOrCreate(['email' => 'moderator@mail.ru'], [
            'name'     => 'Модератор',
            'password' => 'password',
            'role_id'  => Role::where('slug', Role::MODERATOR)->value('id'),
        ]);

        User::updateOrCreate(['email' => 'reader@mail.ru'], [
            'name'     => 'Читатель',
            'password' => 'password',
            'role_id'  => Role::where('slug', Role::READER)->value('id'),
        ]);

        // Пользователи, зарегистрированные до появления ролей, становятся читателями.
        User::whereNull('role_id')->update([
            'role_id' => Role::where('slug', Role::READER)->value('id'),
        ]);
    }
}
