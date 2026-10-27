{{-- Шаблон письма со статистикой за день (ЛР14). Стили — в атрибутах style,
     как в emails/new-article. Данные передаются из DailyStatsMail. --}}
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Статистика сайта</title>
</head>
<body style="margin:0; padding:24px; background:#f5f5f5; font-family:Arial, sans-serif; color:#0f0f0f;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; margin:0 auto; background:#ffffff; border:1px solid #e7e7e7; border-radius:8px;">
        <tr>
            <td style="padding:24px;">
                <p style="margin:0 0 8px; font-size:12px; text-transform:uppercase; letter-spacing:1px; color:#767676;">
                    {{ config('app.name') }} · статистика за день
                </p>
                <h1 style="margin:0 0 20px; font-size:22px;">{{ $date->format('d.m.Y') }}</h1>

                <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
                    <tr>
                        <td style="width:50%; padding:14px 16px; background:#f5f5f5; border-radius:6px;">
                            <p style="margin:0; font-size:28px; font-weight:bold;">{{ $viewsTotal }}</p>
                            <p style="margin:4px 0 0; color:#767676;">просмотров статей</p>
                        </td>
                        <td style="width:12px;"></td>
                        <td style="width:50%; padding:14px 16px; background:#f5f5f5; border-radius:6px;">
                            <p style="margin:0; font-size:28px; font-weight:bold;">{{ $commentsToday }}</p>
                            <p style="margin:4px 0 0; color:#767676;">новых комментариев</p>
                        </td>
                    </tr>
                </table>

                @if ($viewsByUrl->isNotEmpty())
                    <p style="margin:0 0 8px; font-weight:bold;">Просмотры по адресам</p>
                    <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        @foreach ($viewsByUrl as $row)
                            <tr>
                                <td style="padding:8px 0; border-top:1px solid #e7e7e7; word-break:break-all;">
                                    <a href="{{ $row->url }}" style="color:#0f0f0f;">{{ $row->url }}</a>
                                </td>
                                <td style="padding:8px 0 8px 16px; border-top:1px solid #e7e7e7; text-align:right; white-space:nowrap;">
                                    {{ $row->views }}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    <p style="margin:0; color:#767676;">Сегодня статьи ещё не открывали.</p>
                @endif
            </td>
        </tr>
    </table>
    <p style="max-width:600px; margin:12px auto 0; font-size:12px; color:#767676; text-align:center;">
        Письмо отправлено планировщиком задач автоматически.
    </p>
</body>
</html>
