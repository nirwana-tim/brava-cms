<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('promos:clear-stale-highlights')
    ->daily()
    ->withoutOverlapping();

Schedule::command('media:cleanup-filenames --remove-orphans')
    ->dailyAt('03:30')
    ->withoutOverlapping();
