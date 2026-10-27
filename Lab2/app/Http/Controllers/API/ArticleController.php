<?php

namespace App\Http\Controllers\API;

use App\Events\NewArticleEvent;
use App\Http\Controllers\ArticleController as WebArticleController;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Jobs\VeryLongJob;
use App\Models\Article;
use App\Models\Role;
use App\Models\User;
use App\Notifications\NewArticleNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

/**
 * CRUD новостей для REST API (ЛР15).
 * Скопирован из App\Http\Controllers\ArticleController: namespace изменён на API,
 * вместо redirect/view — response с теми же данными (JSON).
 * Методов create и edit нет: формы — это забота клиента.
 *
 * Ключи кэша общие с сайтом (ЛР13), поэтому берутся из веб-контроллера:
 * статья, созданная через API, сразу видна на сайте, и наоборот.
 */
class ArticleController extends Controller
{
    /**
     * Количество новостей на одной странице.
     */
    private const PER_PAGE = 5;

    /**
     * Сколько секунд хранится в кэше страница списка.
     */
    private const LIST_TTL = 600;

    /**
     * Список новостей с пагинацией.
     * GET /api/articles?page=2
     */
    public function index()
    {
        $page = max(1, (int) request('page', 1));

        $articles = Cache::remember(
            WebArticleController::pageCacheKey($page),
            self::LIST_TTL,
            fn () => Article::latest('date')->latest('id')->paginate(self::PER_PAGE)
        );

        // Пагинатор мог попасть в кэш с сайта — ссылки на страницы ведём на API.
        $articles->withPath(url('/api/articles'));

        return response(['articles' => $articles]);
    }

    /**
     * Сохранение новой новости. Права и валидация — в ArticleRequest.
     * POST /api/articles
     */
    public function store(ArticleRequest $request)
    {
        Gate::authorize('create', Article::class);

        $article = Article::create($request->validated());

        // ЛР13: список изменился — удаляем закэшированные страницы.
        WebArticleController::forgetListCache();

        // ЛР10: письмо модераторам через очередь.
        VeryLongJob::dispatch($article);

        // ЛР11: пуш-уведомление посетителям сайта.
        NewArticleEvent::dispatch($article);

        // ЛР12: уведомление в БД всем читателям, кроме автора.
        $readers = User::whereRelation('role', 'slug', Role::READER)
            ->whereKeyNot($request->user()->id)
            ->get();

        Notification::send($readers, new NewArticleNotification($article));

        return response([
            'message' => 'Новость «' . $article->name . '» создана.',
            'article' => $article,
        ], 201);
    }

    /**
     * Одна новость вместе с одобренными комментариями.
     * GET /api/articles/{id}
     */
    public function show(string $article)
    {
        $article = Cache::rememberForever(
            WebArticleController::articleCacheKey($article),
            fn () => Article::with([
                'comments' => fn ($query) => $query->approved()->with('user')->oldest(),
            ])->findOrFail($article)
        );

        return response([
            'article'  => $article->withoutRelations(),
            'comments' => $article->comments,
        ]);
    }

    /**
     * Обновление новости.
     * PUT /api/articles/{id}
     */
    public function update(ArticleRequest $request, Article $article)
    {
        Gate::authorize('update', $article);

        $article->update($request->validated());

        Cache::flush();

        return response([
            'message' => 'Новость «' . $article->name . '» обновлена.',
            'article' => $article,
        ]);
    }

    /**
     * Удаление новости.
     * DELETE /api/articles/{id}
     */
    public function destroy(Article $article)
    {
        Gate::authorize('delete', $article);

        $article->delete();

        Cache::flush();

        return response([
            'message' => 'Новость «' . $article->name . '» удалена.',
        ]);
    }
}
