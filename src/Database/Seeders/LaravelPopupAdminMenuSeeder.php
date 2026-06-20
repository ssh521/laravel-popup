<?php

namespace Ssh521\LaravelPopup\Database\Seeders;

use Illuminate\Database\Seeder;
use Ssh521\LaravelAdmin\Support\AdminPackageRegistrar;

class LaravelPopupAdminMenuSeeder extends Seeder
{
    public function run(): void
    {
        $result = app(AdminPackageRegistrar::class)->register('laravel-popup', [
            'category' => [
                'name' => '팝업 관리',
                'is_active' => true,
                'sort_order' => 760,
            ],
            'menus' => [
                [
                    'name' => '팝업 대시보드',
                    'route_name' => 'popup.admin.dashboard',
                    'url' => '/admin/popups',
                    'sort_order' => 0,
                    'icon' => 'fas fa-window-restore',
                    'description' => '팝업/배너 관리 대시보드',
                ],
                [
                    'name' => '팝업 목록',
                    'route_name' => 'popup.admin.items.index',
                    'url' => '/admin/popups/list',
                    'sort_order' => 10,
                    'icon' => 'fas fa-rectangle-ad',
                    'description' => '사이트 팝업, 배너, 공지 바 관리',
                ],
            ],
            'permissions' => [
                ['name' => 'laravel-popup-dashboard-access', 'description' => '팝업 관리자 대시보드 접근', 'sort_order' => 760],
                ['name' => 'laravel-popup-items-view', 'description' => '팝업 항목 조회', 'sort_order' => 761],
                ['name' => 'laravel-popup-items-create', 'description' => '팝업 항목 생성', 'sort_order' => 762],
                ['name' => 'laravel-popup-items-update', 'description' => '팝업 항목 수정', 'sort_order' => 763],
                ['name' => 'laravel-popup-items-delete', 'description' => '팝업 항목 삭제', 'sort_order' => 764],
                ['name' => 'laravel-popup-items-publish', 'description' => '팝업 항목 활성화', 'sort_order' => 765],
            ],
        ]);

        $this->warnMissingRoles(array_unique([
            ...$result['missing_menu_roles'],
            ...$result['missing_permission_roles'],
        ]));
    }

    /**
     * @param  array<int, string>  $roleNames
     */
    private function warnMissingRoles(array $roleNames): void
    {
        if ($roleNames !== []) {
            $this->command?->warn('laravel-admin RolePermissionSeeder를 먼저 실행하면 Popup 메뉴와 권한이 기본 역할에 할당됩니다.');
        }
    }
}
