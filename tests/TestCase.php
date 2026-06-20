<?php

namespace Ssh521\LaravelPopup\Tests;

use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Ssh521\LaravelAdmin\LaravelAdminServiceProvider;
use Ssh521\LaravelFile\LaravelFileServiceProvider;
use Ssh521\LaravelPopup\LaravelPopupServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            LaravelAdminServiceProvider::class,
            LaravelFileServiceProvider::class,
            LaravelPopupServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('laravel-admin.middleware', ['web']);
    }
}
