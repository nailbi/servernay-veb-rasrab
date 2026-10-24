<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Заполнение таблицы ролей: модератор и читатель.
     */
    public function run(): void
    {
        // updateOrCreate — сидер можно запускать повторно без дублей.
        Role::updateOrCreate(['slug' => Role::MODERATOR], ['name' => 'Модератор']);
        Role::updateOrCreate(['slug' => Role::READER], ['name' => 'Читатель']);
    }
}
