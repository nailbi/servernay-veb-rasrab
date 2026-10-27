<?php

namespace App\Console\Commands;

use App\Mail\DailyStatsMail;
use App\Models\ArticleView;
use App\Models\Comment;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * Рассылка модераторам статистики сайта за день (ЛР14).
 * Создана командой: php artisan make:command SendDailyStats
 * Запускается планировщиком (routes/console.php) или вручную: php artisan stats:daily
 */
class SendDailyStats extends Command
{
    /**
     * Имя и аргументы команды.
     *
     * @var string
     */
    protected $signature = 'stats:daily';

    /**
     * Описание команды (видно в php artisan list).
     *
     * @var string
     */
    protected $description = 'Отправить модераторам статистику за день: просмотры статей и новые комментарии';

    public function handle(): int
    {
        // Запрос к таблице сохранённых url: сколько раз сегодня открывали статьи.
        $viewsTotal = ArticleView::today()->count();

        // Те же просмотры по url — какие статьи читали чаще.
        $viewsByUrl = ArticleView::today()
            ->select('url', DB::raw('count(*) as views'))
            ->groupBy('url')
            ->orderByDesc('views')
            ->limit(10)
            ->get();

        // Запрос к таблице комментариев: сколько добавлено сегодня.
        $commentsToday = Comment::whereDate('created_at', today())->count();

        $moderators = User::whereRelation('role', 'slug', Role::MODERATOR)->pluck('email');

        if ($moderators->isEmpty()) {
            $this->warn('Модераторов нет — письмо отправлять некому.');

            return self::SUCCESS;
        }

        Mail::to($moderators)->send(new DailyStatsMail(
            date: today(),
            viewsTotal: $viewsTotal,
            viewsByUrl: $viewsByUrl,
            commentsToday: $commentsToday,
        ));

        $this->info(sprintf(
            'Статистика за %s отправлена (%s): просмотров — %d, новых комментариев — %d.',
            today()->format('d.m.Y'),
            $moderators->implode(', '),
            $viewsTotal,
            $commentsToday,
        ));

        return self::SUCCESS;
    }
}
