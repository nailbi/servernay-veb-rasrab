<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;

/**
 * CRUD для новостей (ресурсный контроллер).
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
        $article = Article::create($request->validated());

        return redirect()
            ->route('articles.show', $article)
            ->with('success', 'Новость «' . $article->name . '» создана.');
    }

    /**
     * Просмотр одной новости.
     */
    public function show(Article $article)
    {
        return view('articles.show', ['article' => $article]);
    }

    /**
     * Форма редактирования новости.
     */
    public function edit(Article $article)
    {
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
        $article->delete();

        return redirect()
            ->route('articles.index')
            ->with('success', 'Новость «' . $article->name . '» удалена.');
    }
}
