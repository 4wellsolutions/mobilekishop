<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

// Run AI review seeder every hour — processes as many products as API quota allows,
// then auto-resumes from where it left off on the next hourly run.
Schedule::command('reviews:seed')->hourly()->withoutOverlapping()->appendOutputTo(storage_path('logs/review-seeder.log'));
