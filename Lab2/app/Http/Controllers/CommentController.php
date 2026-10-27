<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

/**
 * Комментарии к новостям и интерфейс их модерации.
 */
class CommentController extends Controller
{
    /**
     * Сохранение нового комментария. Он попадает в БД с is_approved = false
     * и не виден на странице, пока его не одобрит модератор.
     */
    public function store(Request $request, Article $article)
    {
        Gate::authorize('create', Comment::class);

        $validated = $request->validate([
            'text' => ['required', 'string', 'min:3', 'max:1000'],
        ], [
            'required' => 'Напишите текст комментария.',
            'min'      => 'Комментарий должен содержать не менее :min символов.',
            'max'      => 'Комментарий должен содержать не более :max символов.',
        ]);

        // Через связь article_id подставляется автоматически.
        $article->comments()->create([
            'text'    => $validated['text'],
            'user_id' => $request->user()->id,
        ]);

        return redirect()
            ->to(route('articles.show', $article) . '#comments')
            ->with('success', 'Спасибо! Комментарий отправлен и ожидает модерации.');
    }

    /**
     * Страница модератора: только комментарии, не прошедшие модерацию.
     */
    public function moderation()
    {
        Gate::authorize('viewAny', Comment::class);

        // with() — жадная загрузка статьи и автора (без N+1 запросов).
        $comments = Comment::pending()->with(['article', 'user'])->oldest()->get();

        return view('comments.moderation', ['comments' => $comments]);
    }

    /**
     * Принять комментарий: он появится на странице статьи.
     */
    public function approve(Comment $comment)
    {
        Gate::authorize('approve', $comment);

        $comment->is_approved = true;
        $comment->save();

        // ЛР13: страница статьи закэширована вместе с комментариями —
        // без этого одобренный комментарий не появился бы на сайте.
        Cache::forget(ArticleController::articleCacheKey($comment->article_id));

        return redirect()
            ->route('comments.moderation')
            ->with('success', 'Комментарий одобрен и опубликован.');
    }

    /**
     * Отклонить комментарий: удаляем его из БД.
     */
    public function reject(Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return redirect()
            ->route('comments.moderation')
            ->with('success', 'Комментарий отклонён и удалён.');
    }
}
