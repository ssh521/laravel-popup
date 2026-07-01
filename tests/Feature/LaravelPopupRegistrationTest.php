<?php

namespace Ssh521\LaravelPopup\Tests\Feature;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ViewErrorBag;
use Spatie\Permission\Models\Role;
use Ssh521\LaravelAdmin\Models\AdminUser;
use Ssh521\LaravelAdmin\Models\Menu;
use Ssh521\LaravelAdmin\Models\MenuCategory;
use Ssh521\LaravelAdmin\Models\Permission;
use Ssh521\LaravelPopup\Database\Seeders\LaravelPopupAdminMenuSeeder;
use Ssh521\LaravelPopup\Models\Popup;
use Ssh521\LaravelPopup\Tests\TestCase;

class LaravelPopupRegistrationTest extends TestCase
{
    /**
     * @var array<int, string>
     */
    private array $permissionNames = [
        'laravel-popup-dashboard-access',
        'laravel-popup-items-view',
        'laravel-popup-items-create',
        'laravel-popup-items-update',
        'laravel-popup-items-delete',
        'laravel-popup-items-publish',
    ];

    public function test_package_routes_are_registered(): void
    {
        $this->assertNotNull(Route::getRoutes()->getByName('popup.admin.dashboard'));
        $this->assertNotNull(Route::getRoutes()->getByName('popup.admin.items.index'));
        $this->assertNotNull(Route::getRoutes()->getByName('popup.admin.items.create'));
    }

    public function test_it_registers_popup_admin_menus_permissions_and_role_links(): void
    {
        $this->seed(LaravelPopupAdminMenuSeeder::class);

        $category = MenuCategory::query()->where('name', '팝업 관리')->firstOrFail();
        $adminRole = Role::query()->where('name', 'Admin')->where('guard_name', 'laravel_admin')->firstOrFail();
        $superAdminRole = Role::query()->where('name', 'Super Admin')->where('guard_name', 'laravel_admin')->firstOrFail();

        $this->assertSame(2, $category->menu_count);
        $this->assertTrue($category->roles()->whereKey($adminRole->id)->exists());
        $this->assertTrue($category->roles()->whereKey($superAdminRole->id)->exists());
        $this->assertSame(2, Menu::query()->where('category_id', $category->id)->count());

        foreach ($this->permissionNames as $permissionName) {
            $permission = Permission::query()->where('name', $permissionName)->where('guard_name', 'laravel_admin')->firstOrFail();

            $this->assertTrue($adminRole->permissions()->whereKey($permission->id)->exists());
            $this->assertTrue($superAdminRole->permissions()->whereKey($permission->id)->exists());
        }
    }

    public function test_admin_routes_have_permission_middleware(): void
    {
        $expected = [
            'popup.admin.dashboard' => 'can:laravel-popup-dashboard-access',
            'popup.admin.items.index' => 'can:laravel-popup-items-view',
            'popup.admin.items.create' => 'can:laravel-popup-items-create',
            'popup.admin.items.store' => 'can:laravel-popup-items-create',
            'popup.admin.items.show' => 'can:laravel-popup-items-view',
            'popup.admin.items.edit' => 'can:laravel-popup-items-update',
            'popup.admin.items.update' => 'can:laravel-popup-items-update',
            'popup.admin.items.duplicate' => 'can:laravel-popup-items-create',
            'popup.admin.items.preview' => 'can:laravel-popup-items-view',
            'popup.admin.items.destroy' => 'can:laravel-popup-items-delete',
        ];

        foreach ($expected as $routeName => $middleware) {
            $this->assertRouteHasMiddleware($routeName, $middleware);
        }
    }

    public function test_popup_html_editor_keeps_body_field_contract(): void
    {
        $html = Blade::render('<x-laravel-popup::admin.html-editor id="body" name="body" value="<p>안내</p>" />');

        $this->assertStringContainsString('name="body"', $html);
        $this->assertStringContainsString('contenteditable="true"', $html);
        $this->assertStringContainsString('x-model="value"', $html);
        $this->assertStringContainsString('laravel-popup:fill-html-editor', $html);
        $this->assertStringContainsString('HTML', $html);
    }

    public function test_popup_form_provides_whole_form_sample_fill(): void
    {
        $html = view('laravel-popup::admin.popups.partials.form', [
            'popup' => new Popup([
                'type' => 'popup',
                'status' => 'draft',
                'position' => 'center',
                'device' => 'all',
                'close_policy' => 'close',
                'link_target' => '_self',
            ]),
            'types' => config('laravel-popup.types', []),
            'statuses' => config('laravel-popup.statuses', []),
            'devices' => config('laravel-popup.devices', []),
            'positions' => config('laravel-popup.positions', []),
            'closePolicies' => config('laravel-popup.close_policies', []),
            'showSampleButton' => true,
            'errors' => new ViewErrorBag,
        ])->render();

        $this->assertStringContainsString('전체 예제 입력', $html);
        $this->assertStringContainsString('fillSample()', $html);
        $this->assertStringContainsString('여름 프로모션 팝업', $html);
        $this->assertStringContainsString('my-10 border-b border-gray-900/10 md:col-span-12 dark:border-white/10', $html);
        $this->assertStringContainsString('laravel-popup:fill-html-editor', $html);
    }

    public function test_popup_form_can_hide_whole_form_sample_fill(): void
    {
        $html = view('laravel-popup::admin.popups.partials.form', [
            'popup' => new Popup([
                'type' => 'popup',
                'status' => 'draft',
                'position' => 'center',
                'device' => 'all',
                'close_policy' => 'close',
                'link_target' => '_self',
            ]),
            'types' => config('laravel-popup.types', []),
            'statuses' => config('laravel-popup.statuses', []),
            'devices' => config('laravel-popup.devices', []),
            'positions' => config('laravel-popup.positions', []),
            'closePolicies' => config('laravel-popup.close_policies', []),
            'showSampleButton' => false,
            'errors' => new ViewErrorBag,
        ])->render();

        $this->assertStringNotContainsString('전체 예제 입력', $html);
        $this->assertStringContainsString('fillSample()', $html);
    }

    public function test_store_redirects_to_popup_list_after_create(): void
    {
        $this->migratePopupTables();
        $this->actingAsPopupAdmin('laravel-popup-items-create');

        $this->post(route('popup.admin.items.store'), $this->validPopupPayload([
            'title' => '등록 후 목록 이동',
        ]))
            ->assertRedirect(route('popup.admin.items.index'));
    }

    public function test_update_redirects_to_popup_list_after_save(): void
    {
        $this->migratePopupTables();
        $this->actingAsPopupAdmin('laravel-popup-items-update');

        $popup = Popup::query()->create([
            'title' => '수정 전',
            'type' => 'popup',
            'status' => 'draft',
            'position' => 'center',
            'device' => 'all',
            'close_policy' => 'close',
            'priority' => 0,
            'sort_order' => 0,
        ]);

        $this->put(route('popup.admin.items.update', $popup), $this->validPopupPayload([
            'title' => '수정 후 목록 이동',
        ]))
            ->assertRedirect(route('popup.admin.items.index'));
    }

    public function test_public_popup_render_uses_inline_centering_and_image_sizing(): void
    {
        $popup = new Popup([
            'title' => '이미지 팝업',
            'type' => 'popup',
            'status' => 'active',
            'display_title' => '이벤트 안내',
            'body' => '<p>본문</p>',
            'image_path' => '/storage/popups/event.jpg',
            'image_alt' => '이벤트 이미지',
            'link_url' => '/event',
            'link_target' => '_self',
            'position' => 'center',
            'device' => 'all',
            'close_policy' => 'close',
            'settings' => [
                'width' => '480px',
                'max_width' => '92vw',
                'background_color' => '#111827',
                'text_color' => '#f9fafb',
                'z_index' => 1050,
            ],
        ]);
        $popup->id = 10;

        $html = view('laravel-popup::public.render', [
            'popups' => new EloquentCollection([$popup]),
        ])->render();

        $this->assertStringContainsString('position: fixed', $html);
        $this->assertStringContainsString('top: 50%', $html);
        $this->assertStringContainsString('left: 50%', $html);
        $this->assertStringContainsString('transform: translate(-50%, -50%)', $html);
        $this->assertStringContainsString('width: 480px', $html);
        $this->assertStringContainsString('max-width: 92vw', $html);
        $this->assertStringContainsString('background-color: #111827', $html);
        $this->assertStringContainsString('color: #f9fafb', $html);
        $this->assertStringContainsString('display: block; width: 100%; max-width: 100%; max-height: 24rem; height: auto; object-fit: cover;', $html);
    }

    public function test_public_popup_types_render_different_layouts(): void
    {
        $popup = new Popup([
            'title' => '카드 팝업',
            'type' => 'popup',
            'status' => 'active',
            'display_title' => '카드형',
            'body' => '<p>카드 본문</p>',
            'position' => 'center',
            'device' => 'all',
            'close_policy' => 'close',
        ]);
        $popup->id = 11;

        $banner = new Popup([
            'title' => '가로 배너',
            'type' => 'banner',
            'status' => 'active',
            'display_title' => '배너형',
            'body' => '<p>배너 본문</p>',
            'image_path' => '/storage/popups/banner.jpg',
            'position' => 'center',
            'device' => 'all',
            'close_policy' => 'close',
        ]);
        $banner->id = 12;

        $noticeBar = new Popup([
            'title' => '상단 공지',
            'type' => 'notice_bar',
            'status' => 'active',
            'display_title' => '공지 바',
            'body' => '<p>공지 본문</p>',
            'position' => 'center',
            'device' => 'all',
            'close_policy' => 'close',
        ]);
        $noticeBar->id = 13;

        $html = view('laravel-popup::public.render', [
            'popups' => new EloquentCollection([$popup, $banner, $noticeBar]),
        ])->render();

        $this->assertStringContainsString('data-popup-layout="popup"', $html);
        $this->assertStringContainsString('width: min(32rem, calc(100vw - 2rem))', $html);
        $this->assertStringContainsString('data-popup-layout="banner"', $html);
        $this->assertStringContainsString('width: min(56rem, calc(100vw - 2rem))', $html);
        $this->assertStringContainsString('height: 6rem; width: 9rem; max-width: 36vw; object-fit: cover;', $html);
        $this->assertStringContainsString('data-popup-layout="notice-bar"', $html);
        $this->assertStringContainsString('data-popup-type="notice_bar"', $html);
        $this->assertStringContainsString('width: 100%', $html);
        $this->assertStringContainsString('top: 0', $html);
    }

    public function test_admin_preview_forces_popup_visible_even_when_close_state_exists(): void
    {
        $banner = new Popup([
            'title' => '미리보기 배너',
            'type' => 'banner',
            'status' => 'draft',
            'display_title' => '배너 미리보기',
            'body' => '<p>배너 본문</p>',
            'position' => 'center',
            'device' => 'all',
            'close_policy' => 'hide_today',
        ]);
        $banner->id = 14;

        $html = view('laravel-popup::admin.popups.preview', [
            'popup' => $banner,
        ])->render();

        $this->assertStringContainsString('data-popup-layout="banner"', $html);
        $this->assertStringContainsString('const previewMode = true', $html);
        $this->assertStringContainsString('const state = previewMode ? {} : getState();', $html);
        $this->assertStringNotContainsString('미리보기 배경', $html);
        $this->assertStringNotContainsString('실제 공개 화면에서는', $html);
    }

    public function test_admin_preview_buttons_open_in_new_window(): void
    {
        $popup = new Popup([
            'title' => '새창 미리보기',
            'type' => 'popup',
            'status' => 'active',
            'position' => 'center',
            'device' => 'all',
            'close_policy' => 'close',
        ]);
        $popup->id = 15;

        $indexHtml = view('laravel-popup::admin.popups.index', [
            'popups' => new \Illuminate\Pagination\LengthAwarePaginator(
                new EloquentCollection([$popup]),
                1,
                20
            ),
            'types' => config('laravel-popup.types', []),
            'statuses' => config('laravel-popup.statuses', []),
            'devices' => config('laravel-popup.devices', []),
            'positions' => config('laravel-popup.positions', []),
        ])->render();
        $showHtml = view('laravel-popup::admin.popups.show', [
            'popup' => $popup,
            'types' => config('laravel-popup.types', []),
            'statuses' => config('laravel-popup.statuses', []),
            'devices' => config('laravel-popup.devices', []),
            'positions' => config('laravel-popup.positions', []),
        ])->render();

        $this->assertStringContainsString('target="_blank"', $indexHtml);
        $this->assertStringContainsString('rel="noopener noreferrer"', $indexHtml);
        $this->assertStringContainsString('target="_blank"', $showHtml);
        $this->assertStringContainsString('rel="noopener noreferrer"', $showHtml);
    }

    public function test_edit_view_shows_delete_button_for_delete_permission(): void
    {
        $this->actingAsPopupAdmin('laravel-popup-items-delete');

        $popup = new Popup([
            'title' => '삭제 가능한 팝업',
            'type' => 'popup',
            'status' => 'draft',
            'position' => 'center',
            'device' => 'all',
            'close_policy' => 'close',
            'link_target' => '_self',
        ]);
        $popup->id = 16;

        $html = view('laravel-popup::admin.popups.edit', [
            'popup' => $popup,
            'types' => config('laravel-popup.types', []),
            'statuses' => config('laravel-popup.statuses', []),
            'devices' => config('laravel-popup.devices', []),
            'positions' => config('laravel-popup.positions', []),
            'closePolicies' => config('laravel-popup.close_policies', []),
            'errors' => new ViewErrorBag,
        ])->render();

        $this->assertStringContainsString('id="popup-edit-form"', $html);
        $this->assertStringContainsString('삭제하기', $html);
        $this->assertStringContainsString('method="post"', $html);
        $this->assertStringContainsString('_method" value="delete"', $html);
        $this->assertStringContainsString('form="popup-edit-form"', $html);
    }

    private function assertRouteHasMiddleware(string $routeName, string $middleware): void
    {
        $route = Route::getRoutes()->getByName($routeName);

        $this->assertNotNull($route, "Route [{$routeName}] was not registered.");
        $this->assertContains($middleware, $route->gatherMiddleware());
    }

    private function migratePopupTables(): void
    {
        $this->artisan('migrate', [
            '--path' => __DIR__.'/../../database/migrations',
            '--realpath' => true,
        ])->run();
    }

    private function actingAsPopupAdmin(string $permission): AdminUser
    {
        $this->seed(LaravelPopupAdminMenuSeeder::class);

        $guard = config('laravel-admin.auth.guard', 'laravel_admin');
        $adminRole = Role::query()
            ->where('name', 'Admin')
            ->where('guard_name', $guard)
            ->firstOrFail();
        $adminRole->syncPermissions([$permission]);

        $admin = AdminUser::query()->forceCreate([
            'name' => 'Popup Admin',
            'email' => $permission.'@example.com',
            'email_verified_at' => now(),
            'password' => 'password',
        ]);
        $admin->assignRole($adminRole);

        $this->actingAs($admin, $guard);

        return $admin;
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPopupPayload(array $overrides = []): array
    {
        return [
            'title' => '테스트 팝업',
            'description' => '테스트 설명',
            'type' => 'popup',
            'status' => 'draft',
            'display_title' => '테스트 표시 제목',
            'body' => '<p>본문</p>',
            'image_path' => '/storage/popups/test.jpg',
            'image_alt' => '테스트 이미지',
            'link_url' => '/event',
            'link_target' => '_self',
            'position' => 'center',
            'device' => 'all',
            'include_paths' => "/\n/event/*",
            'exclude_paths' => '/admin/*',
            'close_policy' => 'close',
            'close_duration' => null,
            'priority' => 0,
            'sort_order' => 0,
            'width' => '480px',
            'max_width' => '92vw',
            'background_color' => '#ffffff',
            'text_color' => '#111827',
            'z_index' => 1050,
            ...$overrides,
        ];
    }
}
