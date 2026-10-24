<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Политика доступа к новостям.
 * Модератору всё разрешено ещё раньше — в Gate::before (AppServiceProvider),
 * поэтому до этих методов доходят только остальные пользователи.
 */
class ArticlePolicy
{
    /**
     * Просмотр списка новостей — всем, в том числе гостям (?User).
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Просмотр одной новости — всем.
     */
    public function view(?User $user, Article $article): bool
    {
        return true;
    }

    /**
     * Создание новости — только модератору.
     */
    public function create(User $user): Response
    {
        return $user->isModerator()
            ? Response::allow()
            : Response::deny('Создавать новости может только модератор.');
    }

    /**
     * Изменение новости — только модератору.
     */
    public function update(User $user, Article $article): Response
    {
        return $user->isModerator()
            ? Response::allow()
            : Response::deny('Редактировать новости может только модератор.');
    }

    /**
     * Удаление новости — только модератору.
     */
    public function delete(User $user, Article $article): Response
    {
        return $user->isModerator()
            ? Response::allow()
            : Response::deny('Удалять новости может только модератор.');
    }
}
