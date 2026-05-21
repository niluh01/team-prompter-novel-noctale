<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auto-publish scheduled chapters that are due
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('chapters')) {
                \App\Models\Chapter::where('publish_status', 'scheduled')
                    ->where('scheduled_at', '<=', now())
                    ->update(['publish_status' => 'published']);
            }
        } catch (\Exception $e) {
            // Prevent failure during migrations or database absence
        }
    }
}
