<?php

namespace Ssh521\LaravelPopup\Tests\Feature;

use Illuminate\Support\Facades\Route;
use Ssh521\LaravelPopup\Tests\TestCase;

class LaravelPopupRegistrationTest extends TestCase
{
    public function test_package_routes_are_registered(): void
    {
        $this->assertNotNull(Route::getRoutes()->getByName('popup.admin.dashboard'));
        $this->assertNotNull(Route::getRoutes()->getByName('popup.admin.items.index'));
        $this->assertNotNull(Route::getRoutes()->getByName('popup.admin.items.create'));
    }

    public function test_admin_routes_have_permission_middleware(): void
    {
        $this->assertRouteHasMiddleware('popup.admin.dashboard', 'can:laravel-popup-dashboard-access');
        $this->assertRouteHasMiddleware('popup.admin.items.index', 'can:laravel-popup-items-view');
        $this->assertRouteHasMiddleware('popup.admin.items.create', 'can:laravel-popup-items-create');
        $this->assertRouteHasMiddleware('popup.admin.items.edit', 'can:laravel-popup-items-update');
        $this->assertRouteHasMiddleware('popup.admin.items.destroy', 'can:laravel-popup-items-delete');
    }

    private function assertRouteHasMiddleware(string $routeName, string $middleware): void
    {
        $route = Route::getRoutes()->getByName($routeName);

        $this->assertNotNull($route, "Route [{$routeName}] was not registered.");
        $this->assertContains($middleware, $route->gatherMiddleware());
    }
}
