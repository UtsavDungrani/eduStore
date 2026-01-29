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
        // Share site settings with all views
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $settings = \Illuminate\Support\Facades\Cache::rememberForever('site_settings', function () {
                return \App\Models\Setting::pluck('value', 'key')->all();
            });

            $view->with('siteSettings', $settings);
            $view->with('brandColor', '#2C1810'); // Hardcoded for theme consistency
            $view->with('secondaryColor', '#D4AF37'); // Gold Accent
            $view->with('siteName', 'EduStore');
        });
    }
}
