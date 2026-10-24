<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Свой ответ на отказ в доступе (AuthorizationException → 403):
        // показываем сообщение из политики или шлюза на собственной странице.
        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 403);
            }

            return response()->view('errors.403', [
                'message' => $e->getMessage() ?: 'У вас нет прав для этого действия.',
            ], 403);
        });
    })->create();
