<?php

namespace App\Jobs;

use App\Mail\NewArticleMail;
use App\Models\Article;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Задание очереди: отправка письма модераторам о новой статье.
 * Создано командой: php artisan make:job VeryLongJob
 *
 * ShouldQueue — задание не выполняется сразу, а записывается в таблицу jobs
 * (QUEUE_CONNECTION=database). Выполняет его воркер: php artisan queue:work
 */
class VeryLongJob implements ShouldQueue
{
    use Queueable;

    /**
     * Сколько раз пытаться выполнить задание при ошибке (например, SMTP недоступен).
     */
    public int $tries = 3;

    /**
     * Статья передаётся в конструктор. В очередь сохраняется только её id
     * (SerializesModels внутри Queueable), а перед выполнением модель
     * заново загружается из БД.
     */
    public function __construct(public Article $article)
    {
    }

    /**
     * Выполнение задания — этот код запускает воркер очереди.
     */
    public function handle(): void
    {
        $moderators = User::whereRelation('role', 'slug', Role::MODERATOR)->pluck('email');

        if ($moderators->isEmpty()) {
            return;
        }

        Mail::to($moderators)->send(new NewArticleMail($this->article));

        Log::info('VeryLongJob: письмо о статье «' . $this->article->name . '» отправлено модераторам.');
    }
}
