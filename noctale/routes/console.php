<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;
use App\Models\Chapter;

Schedule::call(function () {
    Chapter::where('publish_status', 'scheduled')
        ->where('scheduled_at', '<=', now())
        ->update(['publish_status' => 'published']);
})->everyMinute();

Artisan::command('novels:sync-views', function () {
    \App\Models\Novel::all()->each(function ($n) {
        $n->update(['views' => $n->chapters()->sum('views')]);
    });
    $this->info('Novel views synced successfully!');
})->purpose('Sync all novel views with the sum of their chapter views');
