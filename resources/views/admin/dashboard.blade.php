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

    <div class="w-full bg-white px-2 py-2 dark:bg-gray-900">
        <div class="min-h-[560px] bg-white px-4 py-6 sm:px-6 lg:px-8 dark:bg-gray-900">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">팝업 관리</h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">사이트 팝업, 배너, 공지 바 노출 상태를 관리합니다.</p>
                </div>
                <a href="{{ route('popup.admin.items.create') }}" class="mt-4 inline-flex h-9 items-center rounded-md bg-indigo-600 px-3 text-sm font-semibold !text-white shadow-sm hover:bg-indigo-500 hover:no-underline sm:mt-0">
                    <i class="fa-solid fa-plus mr-2 text-xs" aria-hidden="true"></i>
                    등록하기
                </a>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">전체 항목</div>
                    <div class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($totalCount) }}</div>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">현재 노출 가능</div>
                    <div class="mt-2 text-3xl font-semibold text-green-600 dark:text-green-300">{{ number_format($activeCount) }}</div>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">초안</div>
                    <div class="mt-2 text-3xl font-semibold text-amber-600 dark:text-amber-300">{{ number_format($draftCount) }}</div>
                </div>
            </div>

            <div class="mt-8">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">최근 항목</h2>
                    <a href="{{ route('popup.admin.items.index') }}" class="text-sm font-semibold !text-indigo-600 hover:no-underline dark:!text-indigo-300">목록 보기</a>
                </div>
                <div class="mt-3 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-800 dark:bg-gray-900">
                            @forelse($recentPopups as $popup)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $popup->title }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ config("laravel-popup.types.{$popup->type}", $popup->type) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ config("laravel-popup.statuses.{$popup->status}", $popup->status) }}</td>
                                    <td class="px-4 py-3 text-right text-sm">
                                        <a href="{{ route('popup.admin.items.edit', $popup) }}" class="font-semibold !text-indigo-600 hover:no-underline dark:!text-indigo-300">수정</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">등록된 항목이 없습니다.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-laravel-admin::admin.layouts.admin>
