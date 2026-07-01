<x-laravel-admin::admin.layouts.admin title="팝업 관리">
    <x-slot name="header">
        <x-laravel-admin::admin.admin-header>
            <x-slot name="navigation">
                <a href="{{ route('admin.index') }}">관리자 홈</a>
                - <a href="{{ route('popup.admin.dashboard') }}">팝업 관리</a>
            </x-slot>
            <x-slot name="description">Popup Dashboard</x-slot>
        </x-laravel-admin::admin.admin-header>
    </x-slot>

    <x-laravel-admin::admin.page-section title="팝업 관리" description="사이트 팝업, 배너, 공지 바 노출 상태를 관리합니다.">
        <x-slot name="actions">
            <x-laravel-admin::admin.action-button :href="route('popup.admin.items.create')" icon="plus">
                등록하기
            </x-laravel-admin::admin.action-button>
        </x-slot>

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <x-laravel-admin::admin.stat label="전체 항목" :value="number_format($totalCount)" />
                <x-laravel-admin::admin.stat label="현재 노출 가능" :value="number_format($activeCount)" />
                <x-laravel-admin::admin.stat label="초안" :value="number_format($draftCount)" />
            </div>

            <div class="mt-8">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">최근 항목</h2>
                    <x-laravel-admin::admin.action-button variant="link" size="sm" :href="route('popup.admin.items.index')" icon="list">
                        목록
                    </x-laravel-admin::admin.action-button>
                </div>
                <x-laravel-admin::admin.table-shell class="mt-3">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-800 dark:bg-gray-900">
                            @forelse($recentPopups as $popup)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $popup->title }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ config("laravel-popup.types.{$popup->type}", $popup->type) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ config("laravel-popup.statuses.{$popup->status}", $popup->status) }}</td>
                                    <td class="px-4 py-3 text-right text-sm">
                                        <x-laravel-admin::admin.action-button variant="link" size="sm" :href="route('popup.admin.items.edit', $popup)" icon="pen-to-square" class="h-auto px-2 py-1">
                                            수정하기
                                        </x-laravel-admin::admin.action-button>
                                    </td>
                                </tr>
                            @empty
                                <x-laravel-admin::admin.table-empty-row colspan="4" message="등록된 항목이 없습니다." />
                            @endforelse
                        </tbody>
                    </table>
                </x-laravel-admin::admin.table-shell>
            </div>
    </x-laravel-admin::admin.page-section>
</x-laravel-admin::admin.layouts.admin>
