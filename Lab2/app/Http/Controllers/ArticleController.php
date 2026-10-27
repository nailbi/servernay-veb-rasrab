<?php

namespace App\Http\Controllers;

use App\Events\NewArticleEvent;
use App\Http\Requests\ArticleRequest;
use App\Jobs\VeryLongJob;
use App\Models\Article;
use App\Models\Role;
use App\Models\User;
use App\Notifications\NewArticleNotification;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

/**
 * CRUD для новостей (ресурсный контроллер).
 * Права проверяются через ArticlePolicy: Gate::authorize() бросает
 * AuthorizationException (ответ 403), если политика вернула отказ.
 */
class ArticleController extends Controller
{
    /**
     * Количество новостей на одной странице.
     */
    private const PER_PAGE = 5;

    /**
     * Список новостей с пагинацией: сначала свежие.
     */
    public function index()
    {
        $articles = Article::latest('date')->latest('id')->paginate(self::PER_PAGE);

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
     */
    public function show(Article $article)
    {
        // На странице — только комментарии, прошедшие модерацию.
        $comments = $article->comments()->approved()->with('user')->oldest()->get();

        return view('articles.show', [
            'article'  => $article,
            'comments' => $comments,
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

        return redirect()
            ->route('articles.index')
            ->with('success', 'Новость «' . $article->name . '» удалена.');
    }
}
