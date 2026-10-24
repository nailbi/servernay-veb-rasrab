<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Mail\NewArticleMail;
use App\Models\Article;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Gate;

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

        // После сохранения статьи — письмо всем модераторам.
        $moderators = User::whereRelation('role', 'slug', Role::MODERATOR)->pluck('email');

        if ($moderators->isNotEmpty()) {
            Mail::to($moderators)->send(new NewArticleMail($article));
        }

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
