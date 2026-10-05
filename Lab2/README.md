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
