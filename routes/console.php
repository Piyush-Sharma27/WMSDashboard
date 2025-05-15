<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\GrnOldData;
use Carbon\Carbon;

// this console is responsible for the commands
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();



// Schedule::command('SyncHourlyGrn')->everyFiveMinutes();


// Schedule::command('GrnoldData')->dailyAt('02:00');


Schedule::command('SyncHourlyGrn')->hourly();

