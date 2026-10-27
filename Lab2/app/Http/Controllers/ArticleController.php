<?php

namespace App\Http\Controllers;

use App\Events\NewArticleEvent;
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
 * CRUD для новостей (ресурсный контроллер).
 * Права проверяются через ArticlePolicy: Gate::authorize() бросает
 * AuthorizationException (ответ 403), если политика вернула отказ.
 *
 * ЛР13: список и страница статьи кэшируются (CACHE_STORE=database, таблица cache).
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
     * Ключ кэша для страницы пагинации: articles:page:1, articles:page:2, …
     */
    public static function pageCacheKey(int $page): string
    {
        return 'articles:page:' . $page;
    }

    /**
     * Ключ кэша для страницы просмотра статьи (вместе с комментариями).
     */
    public static function articleCacheKey(int|string $id): string
    {
        return 'article:' . $id;
    }

    /**
     * Удаление из кэша всех страниц списка (включая пагинацию).
     * Новая статья встаёт первой и сдвигает все страницы, поэтому чистим каждую.
     */
    public static function forgetListCache(): void
    {
        $pages = max(1, (int) ceil(Article::count() / self::PER_PAGE));

        for ($page = 1; $page <= $pages + 1; $page++) {
            Cache::forget(self::pageCacheKey($page));
        }
    }

    /**
     * Список новостей с пагинацией: сначала свежие.
     * Каждая страница пагинации кэшируется отдельно (remember).
     */
    public function index()
    {
        $page = max(1, (int) request('page', 1));

        $articles = Cache::remember(
            self::pageCacheKey($page),
            self::LIST_TTL,
            fn () => Article::latest('date')->latest('id')->paginate(self::PER_PAGE)
        );

        return view('articles.index', ['articles' => $articles]);
    }

    /**
     * Форма создания новости.
     */
    public function create()
    {
        Gate::authorize('create', Article::class);

        return view('articles.create', [
            'article' => new Article(['date' => now()]),
            'images'  => ArticleRequest::images(),
        ]);
    }

    /**
     * Сохранение новой новости после валидации.
     */
    public function store(ArticleRequest $request)
    {
        Gate::authorize('create', Article::class);

        $article = Article::create($request->validated());

        // ЛР13: список изменился — удаляем закэшированные страницы (forget).
        self::forgetListCache();

        // Письмо модераторам отправляется не здесь, а в очереди:
        // задание пишется в таблицу jobs, пользователь не ждёт SMTP.
        VeryLongJob::dispatch($article);

        // ЛР11: пуш-уведомление всем, кто сейчас на сайте (канал test).
        NewArticleEvent::dispatch($article);

        // ЛР12: уведомление в БД всем читателям, кроме того, кто создал статью.
        $readers = User::whereRelation('role', 'slug', Role::READER)
            ->whereKeyNot($request->user()->id)
            ->get();

        Notification::send($readers, new NewArticleNotification($article));

        return redirect()
            ->route('articles.show', $article)
            ->with('success', 'Новость «' . $article->name . '» создана.');
    }

    /**
     * Просмотр одной новости.
     * Статья вместе с одобренными комментариями кэшируется без срока (rememberForever).
     * Поэтому здесь id, а не привязка модели: при попадании в кэш запроса к БД нет.
     */
    public function show(string $article)
    {
        $article = Cache::rememberForever(
            self::articleCacheKey($article),
            fn () => Article::with([
                // На странице — только комментарии, прошедшие модерацию.
                'comments' => fn ($query) => $query->approved()->with('user')->oldest(),
            ])->findOrFail($article)
        );

        return view('articles.show', [
            'article'  => $article,
            'comments' => $article->comments,
        ]);
    }

    /**
     * Форма редактирования новости.
     */
    public function edit(Article $article)
    {
        Gate::authorize('update', $article);

        return view('articles.edit', [
            'article' => $article,
            'images'  => ArticleRequest::images(),
        ]);
    }

    /**
     * Обновление новости после валидации.
     */
    public function update(ArticleRequest $request, Article $article)
    {
        Gate::authorize('update', $article);

        $article->update($request->validated());

        // ЛР13: данные статьи могли быть в любом кэше — чистим весь (flush).
        Cache::flush();

        return redirect()
            ->route('articles.show', $article)
            ->with('success', 'Новость «' . $article->name . '» обновлена.');
    }

    /**
     * Удаление новости.
     */
    public function destroy(Article $article)
    {
        Gate::authorize('delete', $article);

        $article->delete();

        // ЛР13: статья пропала из списка и со своей страницы — чистим кэш (flush).
        Cache::flush();

        return redirect()
            ->route('articles.index')
            ->with('success', 'Новость «' . $article->name . '» удалена.');
    }
}
