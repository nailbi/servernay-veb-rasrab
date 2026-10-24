<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Хук шлюза: выполняется ПРЕЖДЕ всех остальных проверок (шлюзов и политик).
        // true — доступ разрешён сразу; null — решение принимает политика или шлюз.
        // Возвращать false здесь нельзя: это запретило бы всё всем остальным.
        Gate::before(function (User $user, string $ability) {
            return $user->isModerator() ? true : null;
        });

        // Шлюз для пунктов меню и страниц модератора (своё сообщение об отказе).
        Gate::define('moderate', function (User $user) {
            return $user->isModerator()
                ? Response::allow()
                : Response::deny('Раздел доступен только модератору.');
        });
    }
}
