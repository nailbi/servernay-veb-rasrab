{{-- Шаблон письма модератору. Почтовые клиенты плохо понимают внешние CSS,
     поэтому стили — прямо в атрибутах style. $article передаётся из NewArticleMail. --}}
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Новая статья</title>
</head>
<body style="margin:0; padding:24px; background:#f5f5f5; font-family:Arial, sans-serif; color:#0f0f0f;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; margin:0 auto; background:#ffffff; border:1px solid #e7e7e7; border-radius:8px;">
        <tr>
            <td style="padding:24px;">
                <p style="margin:0 0 8px; font-size:12px; text-transform:uppercase; letter-spacing:1px; color:#767676;">
                    {{ config('app.name') }} · новая статья
                </p>
                <h1 style="margin:0 0 16px; font-size:22px;">{{ $article->name }}</h1>

                <p style="margin:0 0 8px; color:#767676;">Дата: {{ $article->date->format('d.m.Y') }}</p>
                <p style="margin:0 0 16px; font-weight:bold;">{{ $article->short_desc }}</p>
                <p style="margin:0 0 24px; line-height:1.6;">{!! nl2br(e($article->desc)) !!}</p>

                <a href="{{ route('articles.show', $article) }}"
                   style="display:inline-block; padding:10px 18px; background:#0f0f0f; color:#ffffff; text-decoration:none; border-radius:6px;">
                    Открыть статью на сайте
                </a>
            </td>
        </tr>
    </table>
    <p style="max-width:600px; margin:12px auto 0; font-size:12px; color:#767676; text-align:center;">
        Письмо отправлено автоматически, отвечать на него не нужно.
    </p>
</body>
</html>
