<?php

use App\Http\Middleware\TrackArticleView;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        // ЛР15: маршруты REST API, префикс /api.
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ЛР14: регистрация посредника (в Laravel 11+ вместо Kernel.php).
        $middleware->alias([
            'track.view' => TrackArticleView::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ЛР15: на /api/* ошибки всегда в JSON (401, 404, 422…), даже если
        // клиент не прислал заголовок Accept: application/json.
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson()
        );

        // Свой ответ на отказ в доступе (AuthorizationException → 403):
        // показываем сообщение из политики или шлюза на собственной странице.
        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 403);
            }

            return response()->view('errors.403', [
                'message' => $e->getMessage() ?: 'У вас нет прав для этого действия.',
            ], 403);
        });
    })->create();
