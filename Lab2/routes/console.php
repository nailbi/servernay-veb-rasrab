<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// ЛР14: статистика за день модераторам. В Laravel 11+ расписание задаётся здесь,
// а не в app/Console/Kernel.php. По заданию — каждую минуту (для проверки);
// в реальном проекте было бы ->dailyAt('23:55').
// Запуск планировщика: php artisan schedule:run (разово) или schedule:work.
Schedule::command('stats:daily')->everyMinute();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
