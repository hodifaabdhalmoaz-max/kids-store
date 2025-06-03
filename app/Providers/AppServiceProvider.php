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
        // Load translation helper
        require_once app_path('Helpers/TranslationHelper.php');

        // Set default locale to Arabic
        app()->setLocale('ar');

        // Share translation helper with all views
        view()->share('trans', \App\Helpers\TranslationHelper::class);

        // Share common translations with all views
        view()->composer('*', function ($view) {
            $view->with('commonTrans', \App\Helpers\TranslationHelper::getCommonTranslations());
            $view->with('isRtl', \App\Helpers\TranslationHelper::isRtl());
            $view->with('direction', \App\Helpers\TranslationHelper::getDirection());
        });
    }
}
