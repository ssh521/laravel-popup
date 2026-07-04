<x-laravel-admin::admin.layouts.admin title="팝업 목록">
    <x-slot name="header">
        <x-laravel-admin::admin.admin-header>
            <x-slot name="navigation">
                <a href="{{ route('admin.index') }}">관리자 홈</a>
                - <a href="{{ route('popup.admin.dashboard') }}">팝업 관리</a>
            </x-slot>
            <x-slot name="description">Popup List</x-slot>
        </x-laravel-admin::admin.admin-header>
    </x-slot>

    <x-laravel-admin::admin.page-section title="팝업 목록" description="팝업, 배너, 공지 바의 노출 조건과 상태를 관리합니다.">
        <x-slot name="actions">
            <x-laravel-admin::admin.action-button :href="route('popup.admin.items.create')" icon="plus">
                등록하기
            </x-laravel-admin::admin.action-button>
        </x-slot>

        <x-laravel-admin::admin.session-messages />

        <x-laravel-admin::admin.filter-bar action="{{ route('popup.admin.items.index') }}" class="mt-6">
            <x-laravel-admin::admin.form-select name="type" class="lg:w-36 lg:shrink-0">
                <option value="">전체 타입</option>
                @foreach($types as $key => $label)
                    <option value="{{ $key }}" @selected(request('type') === $key)>{{ $label }}</option>
                @endforeach
            </x-laravel-admin::admin.form-select>
            <x-laravel-admin::admin.form-select name="status" class="lg:w-36 lg:shrink-0">
                <option value="">전체 상태</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                @endforeach
            </x-laravel-admin::admin.form-select>
            <x-laravel-admin::admin.form-select name="device" class="lg:w-40 lg:shrink-0">
                <option value="">전체 디바이스</option>
                @foreach($devices as $key => $label)
                    <option value="{{ $key }}" @selected(request('device') === $key)>{{ $label }}</option>
                @endforeach
            </x-laravel-admin::admin.form-select>
            <x-laravel-admin::admin.form-input name="search" value="{{ request('search') }}" placeholder="제목 검색" class="min-w-0 lg:flex-1" />
            <x-laravel-admin::admin.action-button type="submit" variant="search" icon="magnifying-glass" class="shrink-0 whitespace-nowrap">
                검색
            </x-laravel-admin::admin.action-button>
        </x-laravel-admin::admin.filter-bar>

        <x-laravel-admin::admin.table-shell class="mt-6">
            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                <thead class="border-y border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800/80">
                    <tr>
                        <th class="py-3 pr-3 pl-4 text-center text-sm font-semibold text-gray-900 sm:pl-0 dark:text-white">항목</th>
                        <th class="px-3 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">타입</th>
                        <th class="px-3 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">상태</th>
                        <th class="hidden px-3 py-3 text-left text-sm font-semibold text-gray-900 md:table-cell dark:text-white">노출 조건</th>
                        <th class="relative py-3 pr-4 pl-3 sm:pr-0"><span class="sr-only">관리</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-800 dark:bg-gray-900">
                    @forelse($popups as $popup)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/80">
                            <td class="py-3 pr-3 pl-4 text-sm sm:pl-0">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $popup->title }}</div>
                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $popup->display_title ?: '표시 제목 없음' }}</div>
                            </td>
                            <td class="px-3 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $types[$popup->type] ?? $popup->type }}</td>
                            <td class="px-3 py-3 text-sm">
                                <x-laravel-admin::admin.badge :variant="$popup->status === 'active' ? 'success' : ($popup->status === 'draft' ? 'warning' : 'neutral')">
                                    {{ $statuses[$popup->status] ?? $popup->status }}
                                </x-laravel-admin::admin.badge>
                            </td>
                            <td class="hidden px-3 py-3 text-sm text-gray-600 md:table-cell dark:text-gray-300">
                                <div>{{ $devices[$popup->device] ?? $popup->device }} / {{ $positions[$popup->position] ?? $popup->position }}</div>
                                <div class="mt-1 text-xs text-gray-500">{{ $popup->starts_at?->format('Y-m-d H:i') ?: '즉시' }} - {{ $popup->ends_at?->format('Y-m-d H:i') ?: '무기한' }}</div>
                            </td>
                            <td class="py-3 pr-4 pl-3 text-right text-sm whitespace-nowrap sm:pr-0">
                                <div class="flex justify-end">
                                    <x-laravel-admin::admin.action-menu>
                                        <x-laravel-admin::admin.dropdown-link :href="route('popup.admin.items.show', $popup)" class="rounded-lg px-6 py-1 text-left text-base leading-6 !text-gray-950 hover:!bg-blue-500 hover:!text-white hover:!no-underline focus:!bg-blue-500 focus:!text-white dark:!text-gray-100">
                                            보기
                                        </x-laravel-admin::admin.dropdown-link>
                                        <x-laravel-admin::admin.dropdown-link :href="route('popup.admin.items.preview', $popup)" target="_blank" rel="noopener noreferrer" class="rounded-lg px-6 py-1 text-left text-base leading-6 !text-gray-950 hover:!bg-blue-500 hover:!text-white hover:!no-underline focus:!bg-blue-500 focus:!text-white dark:!text-gray-100">
                                            미리보기
                                        </x-laravel-admin::admin.dropdown-link>
                                        <x-laravel-admin::admin.dropdown-link :href="route('popup.admin.items.edit', $popup)" class="rounded-lg px-6 py-1 text-left text-base leading-6 !text-gray-950 hover:!bg-blue-500 hover:!text-white hover:!no-underline focus:!bg-blue-500 focus:!text-white dark:!text-gray-100">
                                            수정하기
                                        </x-laravel-admin::admin.dropdown-link>
                                    </x-laravel-admin::admin.action-menu>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <x-laravel-admin::admin.table-empty-row colspan="5" message="등록된 팝업 항목이 없습니다." />
                    @endforelse
                </tbody>
            </table>
        </x-laravel-admin::admin.table-shell>

        @if($popups->hasPages())
            <div class="mt-6 text-sm">{{ $popups->appends(request()->query())->links() }}</div>
        @endif
    </x-laravel-admin::admin.page-section>
</x-laravel-admin::admin.layouts.admin>
