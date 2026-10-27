# ЛР1 — Blade. Route

Учебный сайт на Laravel 11. Отрабатывается работа с **маршрутизатором** и
**шаблонизатором Blade**.

**Автор:** Ведерников Никита Михайлович, группа 251-3210
**Дисциплина:** Серверная веб-разработка

## Что реализовано (по заданию ЛР1)

- [x] Макет-обёртка для страниц сайта — [`resources/views/layouts/app.blade.php`](resources/views/layouts/app.blade.php)
- [x] В навигации макета — ссылки на страницы «О нас» и «Контакты»
- [x] Страница-приветствие — [`resources/views/home.blade.php`](resources/views/home.blade.php)
- [x] Страница «О нас» — [`resources/views/about.blade.php`](resources/views/about.blade.php)
- [x] Рендеринг страниц в маршрутизаторе — [`routes/web.php`](routes/web.php)
- [x] Для «Контактов» — массив данных, переданный методом `view()`
- [x] Вёрстка «Контактов» с динамическим выводом данных — [`resources/views/contacts.blade.php`](resources/views/contacts.blade.php)

## ЛР3 — Blade. Route. Controller

- [x] Дополнительные файлы скачаны в `public`: [`public/articles.json`](public/articles.json), картинки — в [`public/images`](public/images)
- [x] Контроллер создан командой `php artisan make:controller MainController` — [`app/Http/Controllers/MainController.php`](app/Http/Controllers/MainController.php)
- [x] Метод `index` читает json-файл и передаёт данные на главную страницу
- [x] Главная страница свёрстана под вывод данных из json (таблица) — [`resources/views/home.blade.php`](resources/views/home.blade.php)
- [x] В маршрутизаторе `/` вызывает `MainController@index` — [`routes/web.php`](routes/web.php)
- [x] `preview_image` — ссылка на страницу `galery`
- [x] На странице `galery` отображается `full_image` — [`resources/views/galery.blade.php`](resources/views/galery.blade.php)

## ЛР11 — Настройка широковещания (Pusher, Laravel Echo, Vue)

- [x] `composer require pusher/pusher-php-server`, `config/broadcasting.php` — драйвер по умолчанию `pusher`
- [x] Событие [`app/Events/NewArticleEvent.php`](app/Events/NewArticleEvent.php) (`make:event`): `ShouldBroadcast`, статья в конструкторе, публичный `Channel('test')`, `broadcastWith()` → `article {id, name}`
- [x] Событие вызывается в `ArticleController@store` после создания статьи
- [x] `npm install vue laravel-echo pusher-js @vitejs/plugin-vue`, [`vite.config.js`](vite.config.js)
- [x] Echo настроен в [`resources/js/bootstrap.js`](resources/js/bootstrap.js), Vue подключён в [`resources/js/app.js`](resources/js/app.js)
- [x] Компонент [`resources/js/components/App.vue`](resources/js/components/App.vue) слушает `NewArticleEvent` на канале `test` и показывает всплывающее уведомление
- [x] В макете — `@vite([...])` и `<div id="app">`

Запуск: заполнить `PUSHER_APP_ID/KEY/SECRET` в `.env` (кластер `eu`), затем

```bash
php artisan config:clear
npm install && npm run build   # или npm run watch в отдельном терминале
php artisan queue:work         # трансляция события идёт через очередь
php artisan serve
```

Открыть сайт в одной вкладке, в другой (под модератором) создать статью — в первой всплывёт уведомление.

## ЛР12 — Уведомление читателей о новой статье

- [x] Таблица `notifications` — `php artisan make:notifications-table` (в методичке `notification:table`), модель `User` использует трейт `Notifiable`
- [x] [`app/Notifications/NewArticleNotification.php`](app/Notifications/NewArticleNotification.php) (`make:notification`): статья в конструкторе, `via()` → `database`, `toDatabase()` → id, название и дата статьи
- [x] `ArticleController@store`: `Notification::send()` всем читателям, кроме автора статьи
- [x] В шапке макета — колокольчик со счётчиком и выпадающим списком непрочитанных уведомлений
- [x] Переход по уведомлению — [`NotificationController@read`](app/Http/Controllers/NotificationController.php): `markAsRead()` и открытие статьи

## ЛР13 — Кэширование новостей и комментариев

- [x] `CACHE_STORE=database` (в методичке `CACHE_DRIVER`), таблица `cache` — `php artisan make:cache-table` (бывшая `cache:table`)
- [x] `index` — `Cache::remember()` для каждой страницы пагинации (`articles:page:N`)
- [x] `store` — `Cache::forget()` всех страниц списка
- [x] `show` — `Cache::rememberForever()` статьи вместе с одобренными комментариями (`article:{id}`)
- [x] `update`, `destroy` — `Cache::flush()`
- [x] Дополнительно: при одобрении комментария кэш его статьи удаляется, иначе комментарий не появился бы на странице

## ЛР14 — Планировщик задач

- [x] Модель и миграция [`ArticleView`](app/Models/ArticleView.php) — таблица `article_views` (url, статья, пользователь, ip)
- [x] Посредник [`TrackArticleView`](app/Http/Middleware/TrackArticleView.php) (`make:middleware`) сохраняет каждый просмотр статьи; зарегистрирован как `track.view` в `bootstrap/app.php` (в Laravel 11+ нет `Kernel.php`) и навешан на маршрут `articles.show`
- [x] Команда [`stats:daily`](app/Console/Commands/SendDailyStats.php) (`make:command`): просмотры за сегодня (всего и по url) и комментарии за сегодня → письмо [`DailyStatsMail`](app/Mail/DailyStatsMail.php) модераторам
- [x] Расписание в [`routes/console.php`](routes/console.php): `Schedule::command('stats:daily')->everyMinute()`
- [x] Запуск: `php artisan schedule:run` (разово) или `php artisan schedule:work`; при `MAIL_MAILER=log` письмо пишется в `storage/logs/laravel.log`

## Структура сайта

- **Header:** меню (Главная / О нас / Контакты)
- **Основная часть:**
  - Главная — приветствие и таблица новостей из json-файла
  - Галерея — полноразмерное изображение выбранной новости
  - О нас — текстовая страница
  - Контакты — текстовая страница с динамическими данными
- **Footer:** ФИО, группа

## Запуск

Требуется PHP ^8.2 и Composer.

```bash
cd Lab2
composer install
cp .env.example .env      # Windows: copy .env.example .env
php artisan key:generate
php artisan serve
```

Сайт откроется на http://localhost:8000

## Маршруты

| URL         | Имя маршрута | Представление              |
|-------------|--------------|----------------------------|
| `/`         | `home`       | `MainController@index` → `home.blade.php` |
| `/galery/{id}` | `galery`  | `MainController@galery` → `galery.blade.php` |
| `/about`    | `about`      | `about.blade.php`          |
| `/contacts` | `contacts`   | `contacts.blade.php`       |
