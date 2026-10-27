<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Письмо модераторам со статистикой сайта за день (ЛР14).
 * Создано командой: php artisan make:mail DailyStatsMail
 */
class DailyStatsMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * public-свойства автоматически передаются в blade-шаблон.
     */
    public function __construct(
        public Carbon $date,
        public int $viewsTotal,
        public Collection $viewsByUrl,
        public int $commentsToday,
    ) {
    }

    /**
     * Тема и шаблон письма; отправитель — из MAIL_FROM_* (.env).
     */
    public function build(): static
    {
        return $this
            ->subject('Статистика сайта за ' . $this->date->format('d.m.Y'))
            ->view('emails.daily-stats');
    }
}
