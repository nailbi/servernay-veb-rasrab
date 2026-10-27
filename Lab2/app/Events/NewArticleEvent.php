<?php

namespace App\Events;

use App\Models\Article;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Событие «добавлена новая статья» — вызывает отправку пуш-уведомления.
 * Создано командой: php artisan make:event NewArticleEvent
 *
 * ShouldBroadcast — событие транслируется через Pusher. Трансляция
 * выполняется в очереди (таблица jobs), поэтому нужен php artisan queue:work.
 */
class NewArticleEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * В конструктор передаётся созданная статья.
     */
    public function __construct(public Article $article)
    {
    }

    /**
     * Канал вещания. Публичный Channel (а не PrivateChannel) —
     * уведомление получают все посетители сайта без авторизации.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('test'),
        ];
    }

    /**
     * Данные, которые уйдут в пуш-уведомление (ключ article на клиенте).
     */
    public function broadcastWith(): array
    {
        return [
            'article' => [
                'id'   => $this->article->id,
                'name' => $this->article->name,
            ],
        ];
    }
}
