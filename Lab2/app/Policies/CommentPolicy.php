<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Политика доступа к комментариям.
 * Модератор проходит все проверки раньше — в Gate::before.
 */
class CommentPolicy
{
    /**
     * Оставлять комментарии может любой авторизованный пользователь (читатель).
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Список комментариев на модерации — только модератору.
     */
    public function viewAny(User $user): Response
    {
        return Response::deny('Модерировать комментарии может только модератор.');
    }

    /**
     * Принять комментарий — только модератору.
     */
    public function approve(User $user, Comment $comment): Response
    {
        return Response::deny('Одобрять комментарии может только модератор.');
    }

    /**
     * Отклонить (удалить) комментарий — только модератору.
     */
    public function delete(User $user, Comment $comment): Response
    {
        return Response::deny('Отклонять комментарии может только модератор.');
    }
}
