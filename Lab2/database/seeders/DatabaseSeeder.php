<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Наполнение БД: фабрика создаёт 20 фэйковых новостей.
     */
    public function run(): void
    {
        Article::factory()->count(20)->create();
    }
}
