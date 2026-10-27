<?php

namespace App\Http\Middleware;

use App\Models\ArticleView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Посредник: сохраняет каждый запрос на просмотр статьи в таблицу article_views (ЛР14).
 * Создан командой: php artisan make:middleware TrackArticleView
 * Зарегистрирован под именем track.view в bootstrap/app.php (в Laravel 11+ нет Kernel.php).
 */
class TrackArticleView
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Считаем только успешно открытые статьи: 404 на несуществующий id не пишем.
        if ($response->isSuccessful()) {
            ArticleView::create([
                'url'        => $request->fullUrl(),
                'article_id' => (int) $request->route('article'),
                'user_id'    => $request->user()?->id,
                'ip'         => $request->ip(),
            ]);
        }

        return $response;
    }
}
