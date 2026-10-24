<?php

namespace App\Mail;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Письмо модератору о добавлении новой статьи.
 * Создано командой: php artisan make:mail NewArticleMail
 */
class NewArticleMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * В конструктор передаём статью — она станет доступна в шаблоне.
     * public-свойства Mailable автоматически передаются в blade-шаблон.
     */
    public function __construct(public Article $article)
    {
    }

    /**
     * Сборка письма: отправитель берётся из MAIL_FROM_* (.env),
     * здесь задаём тему и шаблон.
     */
    public function build(): static
    {
        return $this
            ->subject('Новая статья: ' . $this->article->name)
            ->view('emails.new-article');
    }
}
