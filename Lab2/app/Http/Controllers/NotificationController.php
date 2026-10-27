<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

/**
 * Уведомления пользователя о новых статьях (ЛР12).
 */
class NotificationController extends Controller
{
    /**
     * Переход по уведомлению из выпадающего списка: отмечаем его
     * прочитанным и открываем статью.
     * Ищем только среди уведомлений текущего пользователя — чужое не откроется (404).
     */
    public function read(Request $request, string $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);

        $notification->markAsRead();

        // Статью могли удалить после отправки уведомления.
        if (! Article::whereKey($notification->data['article_id'])->exists()) {
            return redirect()
                ->route('articles.index')
                ->with('success', 'Статья «' . $notification->data['article_name'] . '» уже удалена.');
        }

        return redirect()->route('articles.show', $notification->data['article_id']);
    }
}
