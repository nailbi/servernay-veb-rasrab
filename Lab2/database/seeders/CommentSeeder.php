<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Несколько комментариев читателя: часть одобрена, часть ждёт модерации.
     */
    public function run(): void
    {
        $reader = User::where('email', 'reader@mail.ru')->first();

        if (! $reader || Comment::exists()) {
            return;
        }

        $texts = [
            ['Отличная новость, спасибо!', true],
            ['Интересно, будет ли продолжение?', true],
            ['Жду подробностей на сайте.', false],
            ['Хотелось бы больше фотографий.', false],
        ];

        foreach (Article::latest('id')->take(2)->get() as $article) {
            foreach ($texts as [$text, $approved]) {
                $comment = $article->comments()->create(['text' => $text, 'user_id' => $reader->id]);
                $comment->is_approved = $approved;
                $comment->save();
            }
        }
    }
}
