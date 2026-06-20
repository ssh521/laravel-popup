<x-laravel-admin::admin.layouts.admin title="팝업 목록">
    @php
        $statusBadgeClasses = [
            'draft' => 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-500/20',
            'active' => 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20',
            'inactive' => 'bg-gray-50 text-gray-700 ring-gray-500/10 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700',
        ];
    @endphp

    <x-slot name="header">
        <x-laravel-admin::admin.admin-header>
            <x-slot name="navigation">
                <a href="{{ route('admin.index') }}">관리자 홈</a>
                - <a href="{{ route('popup.admin.dashboard') }}">팝업 관리</a>
            </x-slot>
            <x-slot name="description">Popup List</x-slot>
        </x-laravel-admin::admin.admin-header>
    </x-slot>

    <div class="w-full bg-white px-2 py-2 dark:bg-gray-900">
        <div class="min-h-[560px] bg-white px-4 py-6 sm:px-6 lg:px-8 dark:bg-gray-900">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">팝업 목록</h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">팝업, 배너, 공지 바의 노출 조건과 상태를 관리합니다.</p>
                </div>
                <a href="{{ route('popup.admin.items.create') }}" class="mt-4 inline-flex h-9 items-center rounded-md bg-indigo-600 px-3 text-sm font-semibold !text-white shadow-sm hover:bg-indigo-500 hover:no-underline sm:mt-0">
                    <i class="fa-solid fa-plus mr-2 text-xs" aria-hidden="true"></i>
                    등록하기
                </a>
            </div>

            <x-laravel-admin::admin.session-messages />

            <form method="get" class="mt-6 flex flex-col gap-3 rounded-lg border border-gray-200 bg-gray-50 p-4 lg:flex-row lg:flex-nowrap lg:items-center dark:border-gray-700 dark:bg-gray-800/70">
                <select name="type" class="h-10 rounded-md border border-gray-300 bg-white px-3 text-sm lg:w-36 lg:shrink-0 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="">전체 타입</option>
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}" @selected(request('type') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="status" class="h-10 rounded-md border border-gray-300 bg-white px-3 text-sm lg:w-36 lg:shrink-0 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="">전체 상태</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="device" class="h-10 rounded-md border border-gray-300 bg-white px-3 text-sm lg:w-40 lg:shrink-0 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="">전체 디바이스</option>
                    @foreach($devices as $key => $label)
                        <option value="{{ $key }}" @selected(request('device') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <input name="search" value="{{ request('search') }}" placeholder="제목 검색" class="h-10 min-w-0 rounded-md border border-gray-300 bg-white px-3 text-sm lg:flex-1 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                <button type="submit" class="inline-flex h-10 min-w-20 shrink-0 items-center justify-center whitespace-nowrap rounded-md bg-gray-900 px-4 text-sm font-semibold text-white dark:bg-white dark:text-gray-900">
                    <i class="fa-solid fa-magnifying-glass mr-2 text-xs" aria-hidden="true"></i>
                    검색
                </button>
            </form>

            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-0 dark:text-white">항목</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">타입</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">상태</th>
                            <th class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 md:table-cell dark:text-white">노출 조건</th>
                            <th class="relative py-3.5 pr-4 pl-3 sm:pr-0"><span class="sr-only">관리</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-800 dark:bg-gray-900">
                        @forelse($popups as $popup)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/80">
                                <td class="py-4 pr-3 pl-4 text-sm sm:pl-0">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $popup->title }}</div>
                                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $popup->display_title ?: '표시 제목 없음' }}</div>
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $types[$popup->type] ?? $popup->type }}</td>
                                <td class="px-3 py-4 text-sm">
                                    <span class="inline-flex rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $statusBadgeClasses[$popup->status] ?? $statusBadgeClasses['inactive'] }}">{{ $statuses[$popup->status] ?? $popup->status }}</span>
                                </td>
                                <td class="hidden px-3 py-4 text-sm text-gray-600 md:table-cell dark:text-gray-300">
                                    <div>{{ $devices[$popup->device] ?? $popup->device }} / {{ $positions[$popup->position] ?? $popup->position }}</div>
                                    <div class="mt-1 text-xs text-gray-500">{{ $popup->starts_at?->format('Y-m-d H:i') ?: '즉시' }} - {{ $popup->ends_at?->format('Y-m-d H:i') ?: '무기한' }}</div>
                                </td>
                                <td class="py-4 pr-4 pl-3 text-right text-sm whitespace-nowrap sm:pr-0">
                                    <a href="{{ route('popup.admin.items.show', $popup) }}" class="inline-flex px-2 py-1 font-semibold !text-indigo-600 hover:no-underline dark:!text-indigo-300">상세</a>
                                    <a href="{{ route('popup.admin.items.preview', $popup) }}" target="_blank" class="inline-flex px-2 py-1 font-semibold !text-indigo-600 hover:no-underline dark:!text-indigo-300">미리보기</a>
                                    <a href="{{ route('popup.admin.items.edit', $popup) }}" class="inline-flex px-2 py-1 font-semibold !text-indigo-600 hover:no-underline dark:!text-indigo-300">수정</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-3 py-16 text-center text-sm text-gray-500 dark:text-gray-400">등록된 팝업 항목이 없습니다.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6 text-sm">{{ $popups->links() }}</div>
        </div>
    </div>
</x-laravel-admin::admin.layouts.admin>
