<?php

namespace App\Notifications;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Уведомление читателя о новой статье.
 * Создано командой: php artisan make:notification NewArticleNotification
 *
 * Канал database — уведомление записывается в таблицу notifications
 * (миграция: php artisan make:notifications-table, бывшая notification:table)
 * и показывается в выпадающем списке в шапке сайта.
 */
class NewArticleNotification extends Notification
{
    use Queueable;

    /**
     * В конструктор передаётся созданная статья.
     */
    public function __construct(public Article $article)
    {
    }

    /**
     * Каналы доставки уведомления.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Данные, которые сохраняются в столбце data таблицы notifications (JSON).
     * Метод переименован из toArray в toDatabase.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'article_id'   => $this->article->id,
            'article_name' => $this->article->name,
            'article_date' => $this->article->date->format('d.m.Y'),
        ];
    }
}
