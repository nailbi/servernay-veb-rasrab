<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Наполнение БД: роли, пользователи (модератор и читатель),
     * 20 фэйковых новостей из фабрики и комментарии к ним.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);

        Article::factory()->count(20)->create();

        $this->call(CommentSeeder::class);
    }
}
