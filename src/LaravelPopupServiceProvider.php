<?php

namespace Ssh521\LaravelPopup;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Ssh521\LaravelPopup\Services\PopupManager;

class LaravelPopupServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-popup.php', 'laravel-popup');

        $this->app->singleton(PopupManager::class);
        $this->app->alias(PopupManager::class, 'laravel-popup');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-popup');

        $this->registerComponents();
        $this->registerRoutes();
        $this->registerPublishables();
    }

    private function registerComponents(): void
    {
        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components', 'laravel-popup');
    }

    private function registerRoutes(): void
    {
        Route::middleware('web')->group(function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/admin.php');
        });
    }

    private function registerPublishables(): void
    {
        $this->publishes([
            __DIR__.'/../config/laravel-popup.php' => config_path('laravel-popup.php'),
        ], 'laravel-popup-config');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'laravel-popup-migrations');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-popup'),
        ], 'laravel-popup-views');

        $this->publishes([
            __DIR__.'/../database/seeders' => database_path('seeders/vendor/laravel-popup'),
        ], 'laravel-popup-seeders');
    }
}
