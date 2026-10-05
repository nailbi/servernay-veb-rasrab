<?php

namespace App\Http\Controllers;

class MainController extends Controller
{
    /**
     * Главная страница: читает статьи из public/articles.json
     * и передаёт их в шаблон.
     */
    public function index()
    {
        $articles = $this->getArticles();

        return view('home', ['articles' => $articles]);
    }

    /**
     * Страница галереи: показывает full_image выбранной статьи.
     * $id — порядковый номер статьи в json-файле.
     */
    public function galery(int $id)
    {
        $articles = $this->getArticles();

        abort_unless(isset($articles[$id]), 404);

        return view('galery', ['article' => $articles[$id]]);
    }

    /**
     * Чтение и декодирование json-файла со статьями.
     */
    private function getArticles(): array
    {
        $json = file_get_contents(public_path('articles.json'));

        return json_decode($json, true) ?? [];
    }
}
