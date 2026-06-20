<?php

namespace Ssh521\LaravelPopup\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Ssh521\LaravelPopup\Models\Popup;
use Ssh521\LaravelPopup\Services\PopupManager;
use Ssh521\LaravelPopup\Tests\TestCase;

class PopupManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_only_active_popups_matching_request_context(): void
    {
        Popup::create([
            'title' => '노출 대상',
            'type' => 'popup',
            'status' => 'active',
            'position' => 'center',
            'device' => 'mobile',
            'include_paths' => ['/event/*'],
            'exclude_paths' => ['/event/private'],
            'close_policy' => 'hide_today',
            'priority' => 10,
        ]);

        Popup::create([
            'title' => '비활성',
            'type' => 'popup',
            'status' => 'inactive',
            'position' => 'center',
            'device' => 'mobile',
            'include_paths' => ['/event/*'],
        ]);

        $request = request()->create('/event/summer', 'GET', [], [], [], [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) Mobile',
        ]);

        $items = app(PopupManager::class)->forRequest($request);

        $this->assertCount(1, $items);
        $this->assertSame('노출 대상', $items->first()->title);
    }

    public function test_exclude_paths_win_over_include_paths(): void
    {
        Popup::create([
            'title' => '제외 대상',
            'type' => 'popup',
            'status' => 'active',
            'position' => 'center',
            'device' => 'all',
            'include_paths' => ['/event/*'],
            'exclude_paths' => ['/event/private'],
        ]);

        $request = request()->create('/event/private', 'GET');

        $this->assertCount(0, app(PopupManager::class)->forRequest($request));
    }

    public function test_type_limits_are_applied_after_sorting(): void
    {
        config()->set('laravel-popup.public.max_items_per_type.popup', 1);

        Popup::create([
            'title' => '낮은 우선순위',
            'type' => 'popup',
            'status' => 'active',
            'position' => 'center',
            'device' => 'all',
            'priority' => 1,
        ]);

        Popup::create([
            'title' => '높은 우선순위',
            'type' => 'popup',
            'status' => 'active',
            'position' => 'center',
            'device' => 'all',
            'priority' => 10,
        ]);

        $items = app(PopupManager::class)->forRequest(request()->create('/', 'GET'));

        $this->assertCount(1, $items);
        $this->assertSame('높은 우선순위', $items->first()->title);
    }
}
