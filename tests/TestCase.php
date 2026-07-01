<?php

namespace Ssh521\LaravelPopup\Tests;

use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Permission\PermissionServiceProvider;
use Ssh521\LaravelAdmin\Database\Seeders\RolePermissionSeeder;
use Ssh521\LaravelAdmin\LaravelAdminServiceProvider;
use Ssh521\LaravelAdmin\Models\Permission;
use Ssh521\LaravelFile\LaravelFileServiceProvider;
use Ssh521\LaravelPopup\LaravelPopupServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', [
            '--path' => $this->adminPackagePath('database/migrations'),
            '--realpath' => true,
        ])->run();

        $this->seed(RolePermissionSeeder::class);
    }

    protected function getPackageProviders($app): array
    {
        return [
            PermissionServiceProvider::class,
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
        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('a', 32)));
        $app['config']->set('laravel-admin.middleware', ['web']);
        $app['config']->set('cache.default', 'array');
        $app['config']->set('session.driver', 'array');
        $app['config']->set('permission.cache.store', 'array');
        $app['config']->set('permission.models.permission', Permission::class);
    }

    protected function adminPackagePath(string $path = ''): string
    {
        $basePath = dirname(__DIR__, 2).'/laravel-admin';

        return $path === '' ? $basePath : $basePath.'/'.$path;
    }
}
